<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Models\Sale;
use App\Models\Unit;
use App\Models\Brand;
use App\Utils\SmsUtil;
use App\Models\Account;
use App\Models\Product;
use App\Utils\SaleUtil;
use App\Models\CashFlow;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Warranty;
use App\Jobs\SaleMailJob;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Models\AdminAndUser;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Utils\NameSearchUtil;
use App\Models\CustomerLedger;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Models\ProductOpeningStock;
use App\Models\ProductBranchVariant;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    protected $nameSearchUtil;
    protected $saleUtil;
    protected $smsUtil;
    public function __construct(NameSearchUtil $nameSearchUtil, SaleUtil $saleUtil, SmsUtil $smsUtil)
    {
        $this->nameSearchUtil = $nameSearchUtil;
        $this->saleUtil = $saleUtil;
        $this->smsUtil = $smsUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function index2(Request $request)
    {
        if (auth()->user()->permission->sale['sale_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();

            $sales = '';
            $query = DB::table('sales')
                ->leftJoin('branches', 'sales.branch_id', 'branches.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('sales.branch_id', NULL);
                } else {
                    $query->where('sales.branch_id', $request->branch_id);
                }
            }

            if ($request->user_id) {
                $query->where('sales.admin_id', $request->user_id);
            }

            if ($request->customer_id) {
                if ($request->customer_id == 'NULL') {
                    $query->where('sales.customer_id', NULL);
                } else {
                    $query->where('sales.customer_id', $request->customer_id);
                }
            }

            if ($request->payment_status) {
                if ($request->payment_status == 1) {
                    $query->where('sales.due', '=', 0);
                } else {
                    $query->where('sales.due', '>', 0);
                }
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1]));
                $query->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $sales = $query->select(
                    'sales.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'customers.name as customer_name',
                )->where('sales.status', 1)->where('created_by', 1)
                    ->orderBy('id', 'desc')
                    ->get();
            } else {
                $sales = $query->select(
                    'sales.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'customers.name as customer_name',
                )->where('branch_id', auth()->user()->branch_id)
                    ->where('sales.status', 1)->where('created_by', 1)
                    ->orderBy('id', 'desc')
                    ->get();
            }

            return DataTables::of($sales)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                        </button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.show', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i> View</a>';

                    $html .= '<a class="dropdown-item" id="print_packing_slip" href="' . route('sales.packing.slip', [$row->id]) . '"><i class="far fa-money-bill-alt mr-1 text-primary"></i> Packing Slip</a>';

                    if (auth()->user()->permission->sale['shipment_access'] == '1') {
                        $html .= '<a class="dropdown-item" id="edit_shipment" href="' . route('sales.shipment.edit', [$row->id]) . '"><i class="fas fa-truck mr-1 text-primary"></i> Edit Shipping</a>';
                    }

                    if (auth()->user()->branch_id == $row->branch_id) {
                        if ($row->due > 0) {
                            if (auth()->user()->permission->sale['sale_payment'] == '1') {
                                $html .= '<a class="dropdown-item" id="add_payment" href="' . route('sales.payment.modal', [$row->id]) . '" 
                            ><i class="far fa-money-bill-alt mr-1 text-primary"></i> Receive Payment</a>';
                            }
                        }

                        if (auth()->user()->permission->sale['sale_payment'] == '1') {
                            $html .= '<a class="dropdown-item" id="view_payment" data-toggle="modal"
                        data-target="#paymentListModal" href="' . route('sales.payment.view', [$row->id]) . '"><i
                            class="far fa-money-bill-alt mr-1 text-primary"></i> View Payment</a>';
                        }

                        if ($row->sale_return_due > 0) {
                            if (auth()->user()->permission->sale['sale_payment'] == '1') {
                                $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('sales.return.payment.modal', [$row->id]) . '" 
                            ><i class="far fa-money-bill-alt mr-1 text-primary"></i> Pay Return Amount</a>';
                            }
                        }

                        if (auth()->user()->permission->sale['return_access'] == '1') {
                            $html .= '<a class="dropdown-item" href="' . route('sales.returns.create', [$row->id]) . '"><i
                                    class="fas fa-undo-alt mr-1 text-primary"></i> Sale Return</a>';
                        }

                        $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';

                        $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt mr-1 text-primary"></i> Delete</a>';
                    }

                    $html .= '<a class="dropdown-item" id="items_notification" href=""><i
                                    class="fas fa-envelope mr-1 text-primary"></i> New Sale Notification</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) use ($generalSettings) {
                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })
                ->editColumn('invoice_id', function ($row) {
                    $html = '';
                    $html .= $row->invoice_id;
                    $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo mr-1 text-white"></i></span>' : '';
                    return $html;
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('customer',  function ($row) {
                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('total_payable_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_payable_amount . '</b>';
                })
                ->editColumn('paid', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->paid . '</b>';
                })
                ->editColumn('due', function ($row) use ($generalSettings) {
                    return '<b><span class="text-success">' . json_decode($generalSettings->business, true)['currency'] . ($row->due >= 0 ? $row->due :   0.00) . '</span></b>';
                })
                ->editColumn('sale_return_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->sale_return_amount . '</b>';
                })
                ->editColumn('sale_return_due', function ($row) use ($generalSettings) {
                    return '<b><span class="text-danger">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->sale_return_due . '</span></b>';
                })
                ->editColumn('paid_status', function ($row) {
                    $payable = $row->total_payable_amount - $row->sale_return_amount;
                    $html = '';
                    if ($row->due <= 0) {
                        $html .= '<span class="text-success"><b>Paid</b></span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {
                        $html .= '<span class="text-primary"><b>Partial</b></span>';
                    } elseif ($payable == $row->due) {
                        $html .= '<span class="text-danger"><b>Due</b></span>';
                    }
                    return $html;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('sales.show', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row')
                ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'paid', 'due', 'sale_return_amount', 'sale_return_due', 'paid_status'])
                ->make(true);
        }
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $customers = DB::table('customers')->get(['id', 'name', 'phone']);
        return view('sales.index2', compact('branches', 'customers'));
    }

    public function posList(Request $request)
    {
        if (auth()->user()->permission->sale['sale_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();

            $sales = '';
            $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('sales.branch_id', NULL);
                } else {
                    $query->where('sales.branch_id', $request->branch_id);
                }
            }

            if ($request->user_id) {
                $query->where('sales.admin_id', $request->user_id);
            }

            if ($request->customer_id) {
                if ($request->customer_id == 'NULL') {
                    $query->where('sales.customer_id', NULL);
                } else {
                    $query->where('sales.customer_id', $request->customer_id);
                }
            }

            if ($request->payment_status) {
                if ($request->payment_status == 1) {
                    $query->where('sales.due', '=', 0);
                } else {
                    $query->where('sales.due', '>', 0);
                }
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                //$to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
                $to_date = date('Y-m-d', strtotime($date_range[1]));
                $query->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $sales = $query->select(
                    'sales.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'customers.name as customer_name',
                )->where('sales.status', 1)->where('created_by', 2)
                    ->orderBy('id', 'desc')
                    ->get();
            } else {
                $sales = $query->select(
                    'sales.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'customers.name as customer_name',
                )->where('sales.branch_id', auth()->user()->branch_id)->where('created_by', 2)
                    ->where('sales.status', 1)
                    ->orderBy('id', 'desc')
                    ->get();
            }

            return DataTables::of($sales)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.pos.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';

                    $html .= '<a class="dropdown-item" id="print_packing_slip" href="' . route('sales.packing.slip', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Packing Slip</a>';

                    if (auth()->user()->permission->sale['shipment_access'] == '1') {
                        $html .= '<a class="dropdown-item" id="edit_shipment" href="' . route('sales.shipment.edit', [$row->id]) . '"><i
                        class="fas fa-truck text-primary"></i> Edit Shipping</a>';
                    }

                    if (auth()->user()->branch_id == $row->branch_id) {
                        if ($row->due > 0) {
                            if (auth()->user()->permission->sale['sale_payment'] == '1') {
                                $html .= '<a class="dropdown-item" id="add_payment" href="' . route('sales.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Receive Payment</a>';
                            }
                        }

                        if (auth()->user()->permission->sale['sale_payment'] == '1') {
                            $html .= '<a class="dropdown-item" id="view_payment" data-toggle="modal" data-target="#paymentListModal" href="' . route('sales.payment.view', [$row->id]) . '"><i
                            class="far fa-money-bill-alt text-primary"></i> View Payment</a>';
                        }

                        if ($row->sale_return_due > 0) {
                            if (auth()->user()->permission->sale['sale_payment'] == '1') {
                                $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('sales.return.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Pay Return Amount</a>';
                            }
                        }

                        if (auth()->user()->permission->sale['return_access'] == '1') {
                            $html .= '<a class="dropdown-item" href="' . route('sales.returns.create', [$row->id]) . '"><i
                                    class="fas fa-undo-alt text-primary"></i> Sale Return</a>';
                        }

                        $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit</a>';
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    $html .= '<a class="dropdown-item" id="items_notification" href=""><i class="fas fa-envelope text-primary"></i> New Sale Notification</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) use ($generalSettings) {
                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })
                ->editColumn('invoice_id', function ($row) {
                    $html = '';
                    $html .= $row->invoice_id;
                    $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo mr-1 text-white"></i></span>' : '';
                    return $html;
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('customer',  function ($row) {
                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('total_payable_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_payable_amount . '</b>';
                })
                ->editColumn('paid', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->paid . '</b>';
                })
                ->editColumn('due', function ($row) use ($generalSettings) {
                    return '<b><span class="text-success">' . json_decode($generalSettings->business, true)['currency'] . ($row->due >= 0 ? $row->due :   0.00) . '</span></b>';
                })
                ->editColumn('sale_return_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->sale_return_amount . '</b>';
                })
                ->editColumn('sale_return_due', function ($row) use ($generalSettings) {
                    return '<b><span class="text-danger">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->sale_return_due . '</span></b>';
                })
                ->editColumn('paid_status', function ($row) {
                    $payable = $row->total_payable_amount - $row->sale_return_amount;
                    $html = '';
                    if ($row->due <= 0) {
                        $html .= '<span class="text-success"><b>Paid</b></span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {
                        $html .= '<span class="text-primary"><b>Partial</b></span>';
                    } elseif ($payable == $row->due) {
                        $html .= '<span class="text-danger"><b>Due</b></span>';
                    }
                    return $html;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('sales.pos.show', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row')
                ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'paid', 'due', 'sale_return_amount', 'sale_return_due', 'paid_status'])
                ->make(true);
        }
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $customers = DB::table('customers')->get(['id', 'name', 'phone']);
        return view('sales.pos.index', compact('branches', 'customers'));
    }

    public function show($saleId)
    {
        $sale = Sale::with([
            'branch',
            'branch.add_sale_invoice_layout',
            'customer',
            'admin',
            'admin.role',
            'sale_products',
            'sale_products.product',
            'sale_products.product.warranty',
            'sale_products.variant',
            'sale_payments',
        ])->where('id', $saleId)->first();
        return view('sales.ajax_view.product_details_modal', compact('sale'));
    }

    public function posShow($saleId)
    {
        $sale = Sale::with([
            'branch',
            'branch.pos_sale_invoice_layout',
            'customer',
            'admin',
            'admin.role',
            'sale_products',
            'sale_products.product',
            'sale_products.product.warranty',
            'sale_products.variant',
            'sale_payments',
        ])->where('id', $saleId)->first();
        return view('sales.pos.ajax_view.show', compact('sale'));
    }

    // Draft list view 
    public function drafts(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();

            $drafts = '';
            $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id')
                ->leftJoin('admin_and_users', 'sales.admin_id', 'admin_and_users.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('sales.branch_id', NULL);
                } else {
                    $query->where('sales.branch_id', $request->branch_id);
                }
            }

            if ($request->user_id) {
                $query->where('sales.admin_id', $request->user_id);
            }

            if ($request->customer_id) {
                if ($request->customer_id == 'NULL') {
                    $query->where('sales.customer_id', NULL);
                } else {
                    $query->where('sales.customer_id', $request->customer_id);
                }
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
                $query->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $drafts = $query->select(
                    'sales.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'customers.name as customer_name',
                    'admin_and_users.prefix as u_prefix',
                    'admin_and_users.name as u_name',
                    'admin_and_users.last_name as u_last_name',
                )->where('sales.status', 2)
                    ->orderBy('id', 'desc')
                    ->get();
            } else {
                $drafts = $query->select(
                    'sales.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'customers.name as customer_name',
                    'admin_and_users.prefix as u_prefix',
                    'admin_and_users.name as u_name',
                    'admin_and_users.last_name as u_last_name',
                )->where('branch_id', auth()->user()->branch_id)
                    ->where('sales.status', 2)
                    ->orderBy('id', 'desc')
                    ->get();
            }

            return DataTables::of($drafts)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                        </button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.quotations.details', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i>View</a>';

                    if (auth()->user()->branch_id == $row->branch_id) {
                        if ($row->created_by == 1) {
                            $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                        } else {
                            $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                        }
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt mr-1 text-primary"></i>Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>BR</b>)';
                    }
                })
                ->editColumn('customer',  function ($row) {
                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('total_payable_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_payable_amount . '</b>';
                })
                ->editColumn('user', function ($row) {
                    return $row->u_prefix . ' ' . $row->u_name . ' ' . $row->u_last_name;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('sales.quotations.details', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row')
                ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'user'])
                ->make(true);
        }
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('sales.drafts', compact('branches'));
    }

    // Quotations list view 
    public function quotations(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();

            $quotations = '';
            $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id')
                ->leftJoin('admin_and_users', 'sales.admin_id', 'admin_and_users.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('sales.branch_id', NULL);
                } else {
                    $query->where('sales.branch_id', $request->branch_id);
                }
            }

            if ($request->user_id) {
                $query->where('sales.admin_id', $request->user_id);
            }

            if ($request->customer_id) {
                if ($request->customer_id == 'NULL') {
                    $query->where('sales.customer_id', NULL);
                } else {
                    $query->where('sales.customer_id', $request->customer_id);
                }
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
                $query->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $quotations = $query->select(
                    'sales.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'customers.name as customer_name',
                    'admin_and_users.prefix as u_prefix',
                    'admin_and_users.name as u_name',
                    'admin_and_users.last_name as u_last_name',
                )->where('sales.status', 4)
                    ->orderBy('id', 'desc')
                    ->get();
            } else {
                $quotations = $query->select(
                    'sales.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'customers.name as customer_name',
                    'admin_and_users.prefix as u_prefix',
                    'admin_and_users.name as u_name',
                    'admin_and_users.last_name as u_last_name',
                )->where('sales.branch_id', auth()->user()->branch_id)
                    ->where('sales.status', 4)
                    ->orderBy('id', 'desc')
                    ->get();
            }

            return DataTables::of($quotations)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                        </button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.quotations.details', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i> View</a>';

                    if (auth()->user()->branch_id == $row->branch_id) {
                        if ($row->created_by == 1) {
                            $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                        } else {
                            $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                        }
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt mr-1 text-primary"></i> Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>BR</b>)';
                    }
                })
                ->editColumn('customer',  function ($row) {
                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('total_payable_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_payable_amount . '</b>';
                })
                ->editColumn('user', function ($row) {
                    return $row->u_prefix . ' ' . $row->u_name . ' ' . $row->u_last_name;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('sales.quotations.details', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row')
                ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'user'])
                ->make(true);
        }
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('sales.quotations', compact('branches'));
    }

    // Quotation Details
    public function quotationDetails($quotationId)
    {
        $quotation = Sale::with([
            'branch',
            'branch.add_sale_invoice_layout',
            'customer',
            'admin',
            'admin.role',
            'sale_products',
            'sale_products.product',
            'sale_products.variant',
            'sale_payments',
        ])->where('id', $quotationId)->first();
        return view('sales.ajax_view.quotation_details', compact('quotation'));
    }

    // Create sale view
    public function create()
    {
        if (auth()->user()->permission->sale['sale_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $customers = DB::table('customers')
            ->where('status', 1)->select('id', 'name', 'phone')
            ->orderBy('id', 'desc')->get();
        $invoice_schemas = DB::table('invoice_schemas')->get(['format', 'prefix', 'start_from']);
        $accounts = DB::table('accounts')->get(['id', 'name', 'account_number']);
        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);
        return view('sales.create', compact('customers', 'accounts', 'price_groups', 'invoice_schemas'));
    }

    // Add Sale method
    public function store(Request $request)
    {
        //return $request->all();
        $prefixSettings = DB::table('general_settings')
            ->select(['id', 'prefix', 'contact_default_cr_limit', 'send_es_settings'])
            ->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['sale_payment'];

        $branchInvoiceSchema = DB::table('branches')
            ->leftJoin('invoice_schemas', 'branches.invoice_schema_id', 'invoice_schemas.id')
            ->where('branches.id', auth()->user()->branch_id)
            ->select(
                'branches.*',
                'invoice_schemas.id as schema_id',
                'invoice_schemas.prefix',
                'invoice_schemas.format',
                'invoice_schemas.start_from',
            )
            ->first();

        $invoicePrefix = '';
        if ($request->invoice_schema) {
            $invoicePrefix = $request->invoice_schema;
        } else {
            if ($branchInvoiceSchema && $branchInvoiceSchema->prefix !== null) {
                $invoicePrefix = $branchInvoiceSchema->format == 2 ? date('Y') . $branchInvoiceSchema->start_from : $branchInvoiceSchema->prefix . $branchInvoiceSchema->start_from . date('ymd');
            } else {
                $defaultSchemas = DB::table('invoice_schemas')->where('is_default', 1)->first();
                $invoicePrefix = $defaultSchemas->format == 2 ? date('Y') . $defaultSchemas->start_from : $defaultSchemas->prefix . $defaultSchemas->start_from . date('ymd');
            }
        }

        if ($request->product_ids == null) {
            return response()->json(['errorMsg' => 'product table is empty']);
        }

        if ($request->paying_amount < $request->total_payable_amount && !$request->customer_id) {
            return response()->json(['errorMsg' => 'Listed customer is required when sale is due or partial.']);
        }

        if ($request->total_due > $prefixSettings->contact_default_cr_limit) {
            return response()->json(['errorMsg' => 'Due amount exceeds to default credit limit.']);
        }

        // generate invoice ID
        $i = 4;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        $this->validate($request, [
            'status' => 'required',
        ]);

        $addSale = new Sale();
        $addSale->invoice_id = $request->invoice_id ? $request->invoice_id : $invoicePrefix . $invoiceId;
        $addSale->admin_id = auth()->user()->id;
        $addSale->branch_id = auth()->user()->branch_id;

        // $addSale->customer_id = $request->customer_id;
        $addSale->customer_id = $request->customer_id != 0 ? $request->customer_id : NULL;
        $addSale->status = $request->status;

        if ($request->status == 1) {
            $addSale->is_fixed_challen = 1;
        }

        $addSale->pay_term = $request->pay_term;
        $addSale->date = $request->date;
        $addSale->time = date('h:i:s a');
        $addSale->report_date = date('Y-m-d', strtotime($request->date));
        $addSale->pay_term_number = $request->pay_term_number;
        $addSale->total_item = $request->total_item;
        $addSale->net_total_amount = $request->net_total_amount;
        $addSale->order_discount_type = $request->order_discount_type;
        $addSale->order_discount = $request->order_discount;
        $addSale->order_discount_amount = $request->order_discount_amount;
        $addSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
        $addSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $addSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $addSale->shipment_details = $request->shipment_details;
        $addSale->shipment_address = $request->shipment_address;
        $addSale->shipment_status = $request->shipment_status;
        $addSale->delivered_to = $request->delivered_to;
        $addSale->sale_note = $request->sale_note;
        $addSale->payment_note = $request->payment_note;
        $addSale->month = date('F');
        $addSale->year = date('Y');

        $customer = Customer::where('id', $request->customer_id)->first();
        // Update customer due
        if ($request->status == 1) {
            $changedAmount = $request->change_amount > 0 ? $request->change_amount : 0;
            $paidAmount = $request->paying_amount - $changedAmount;
            if ($request->previous_due > 0) {
                $addSale->total_payable_amount = $request->total_invoice_payable;
                if ($paidAmount >= $request->total_invoice_payable) {
                    $addSale->paid = $request->total_invoice_payable;
                    $addSale->due = 0.00;
                    $payingPreviousDue = $paidAmount - $request->total_invoice_payable; // Comming Soon;
                } elseif ($paidAmount < $request->total_invoice_payable) {
                    $addSale->paid = $request->paying_amount;
                    $calcDue = $request->total_invoice_payable - $request->paying_amount;
                    $addSale->due = $calcDue;
                }
            } else {
                $addSale->total_payable_amount = $request->total_payable_amount;
                $addSale->paid = $request->paying_amount ? $request->paying_amount : 0;
                $addSale->change_amount = $request->change_amount > 0 ? $request->change_amount : 0.00;
                $addSale->due = $request->total_due > 0 ? $request->total_due : 0.00;
            }
            $addSale->save();

            if ($customer) {
                $customer->total_sale = $customer->total_sale + $request->total_payable_amount - $request->previous_due;
                $customer->total_paid = $customer->total_paid + ($request->paying_amount ? $request->paying_amount : 0);
                if ($request->paying_amount <= 0) {
                    $customer->total_sale_due = $request->total_payable_amount;
                } else {
                    if ($request->total_due > 0) {
                        $customer->total_sale_due = $request->total_due;
                    } else {
                        $customer->total_sale_due = 0;
                    }
                }

                $customer->save();
                $addCustomerLedger = new CustomerLedger();
                $addCustomerLedger->customer_id = $request->customer_id;
                $addCustomerLedger->sale_id = $addSale->id;
                $addCustomerLedger->save();
            }
        } else {
            $addSale->total_payable_amount = $request->total_invoice_payable;
            $addSale->save();
        }

        // update product quantity
        $quantities = $request->quantities;
        $units = $request->units;
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $unit_discount_types = $request->unit_discount_types;
        $unit_discounts = $request->unit_discounts;
        $unit_discount_amounts = $request->unit_discount_amounts;
        $unit_tax_percents = $request->unit_tax_percents;
        $unit_tax_amounts = $request->unit_tax_amounts;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $unit_prices_exc_tax = $request->unit_prices_exc_tax;
        $unit_prices = $request->unit_prices;
        $subtotals = $request->subtotals;
        $descriptions = $request->descriptions;

        // update product quantity and add sale product
        $branch_id = auth()->user()->branch_id;
        if ($request->status == 1) {
            $this->saleUtil->updateProductBranchStock($request, $branch_id);
        }

        $__index = 0;
        foreach ($product_ids as $product_id) {
            $addSaleProduct = new SaleProduct();
            $addSaleProduct->sale_id = $addSale->id;
            $addSaleProduct->product_id = $product_id;
            $addSaleProduct->product_variant_id = $variant_ids[$__index] != 'noid' ? $variant_ids[$__index] : NULL;
            $addSaleProduct->quantity = $quantities[$__index];
            $addSaleProduct->unit_discount_type = $unit_discount_types[$__index];
            $addSaleProduct->unit_discount = $unit_discounts[$__index];
            $addSaleProduct->unit_discount_amount = $unit_discount_amounts[$__index];
            $addSaleProduct->unit_tax_percent = $unit_tax_percents[$__index];
            $addSaleProduct->unit_tax_amount = $unit_tax_amounts[$__index];
            $addSaleProduct->unit = $units[$__index];
            $addSaleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$__index];
            $addSaleProduct->unit_price_exc_tax = $unit_prices_exc_tax[$__index];
            $addSaleProduct->unit_price_inc_tax = $unit_prices[$__index];
            $addSaleProduct->subtotal = $subtotals[$__index];
            $addSaleProduct->description = $descriptions[$__index] ? $descriptions[$__index] : NULL;
            $addSaleProduct->save();
            $__index++;
        }

        // Add sale payment
        if ($request->status == 1) {
            $this->saleUtil->__getSalePaymentForAddSaleStore($request, $addSale, $paymentInvoicePrefix, $invoiceId);
        }

        $previous_due = $request->previous_due;
        $total_payable_amount = $request->total_payable_amount;
        $paying_amount = $request->paying_amount;
        $total_due = $request->total_due;
        $change_amount = $request->change_amount;

        $sale = Sale::with([
            'customer',
            'branch',
            'branch.add_sale_invoice_layout',
            'sale_products',
            'sale_products.product',
            'sale_products.product.warranty',
            'sale_products.variant',
            'admin'
        ])->where('id', $addSale->id)->first();

        if (
            env('MAIL_ACTIVE') == 'true' &&
            json_decode($prefixSettings->send_es_settings, true)['send_inv_via_email'] == '1'
        ) {
            if ($customer && $customer->email) {
                dispatch(new SaleMailJob($customer->email, $sale));
                // SaleMailJob::dispatch($customer->email, $sale)
                //     ->delay(now()->addSeconds(5));
            }
        }

        if (
            env('SMS_ACTIVE') == 'true' &&
            json_decode($prefixSettings->send_es_settings, true)['send_notice_via_sms'] == '1'
        ) {
            if ($customer && $customer->phone) {
                $this->smsUtil->singleSms($sale);
            }
        }

        if ($request->action == 'save_and_print') {
            if ($request->status == 1) {
                return view('sales.save_and_print_template.sale_print', compact(
                    'sale',
                    'previous_due',
                    'total_payable_amount',
                    'paying_amount',
                    'total_due',
                    'change_amount'
                ));
            } elseif ($request->status == 2) {
                return view('sales.save_and_print_template.draft_print', compact('sale'));
            } elseif ($request->status == 4) {
                return view('sales.save_and_print_template.quotation_print', compact('sale'));
            }
        } else {
            if ($request->status == 1) {
                session()->flash('successMsg', 'Sale created successfully');
                return response()->json(['finalMsg' => 'Sale created successfully']);
            } elseif ($request->status == 2) {
                session()->flash('successMsg', 'Sale draft created successfully');
                return response()->json(['draftMsg' => 'Sale draft created successfully']);
            } elseif ($request->status == 4) {
                session()->flash('successMsg', 'Sale quotation created successfully');
                return response()->json(['quotationMsg' => 'Sale quotation created successfully']);
            }
        }
    }

    // Sale edit view
    public function edit($saleId)
    {
        if (auth()->user()->permission->sale['sale_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $saleId = $saleId;
        $sale = Sale::where('id', $saleId)->select(['id', 'date', 'branch_id'])->first();
        $price_groups = DB::table('price_groups')->where('status', 'Active')->get();
        return view('sales.edit', compact('saleId', 'sale', 'price_groups'));
    }

    // Get editable sale
    public function editableSale($saleId)
    {
        $sale = Sale::with([
            'sale_products',
            'customer',
            'sale_products.product',
            'sale_products.variant',
            'sale_products.product.comboProducts',
            'sale_products.product.comboProducts.parentProduct',
            'sale_products.product.comboProducts.product_variant',
        ])->where('id', $saleId)->first();

        $qty_limits = [];
        foreach ($sale->sale_products as $sale_product) {
            if ($sale->branch) {
                $productBranch = ProductBranch::where('branch_id', $sale->branch_id)
                    ->where('product_id', $sale_product->product_id)->first();
                if ($sale_product->product->type == 2) {
                    $qty_limits[] = 500000;
                } elseif ($sale_product->product_variant_id) {
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $sale_product->product_id)
                        ->where('product_variant_id', $sale_product->product_variant_id)
                        ->first();
                    $qty_limits[] = $productBranchVariant->variant_quantity;
                } else {
                    $qty_limits[] = $productBranch->product_quantity;
                }
            } else {
                $mbProduct = DB::table('products')
                    ->where('id', $sale_product->product_id)->first();
                if ($sale_product->product->type == 2) {
                    $qty_limits[] = 500000;
                } elseif ($sale_product->product_variant_id) {
                    $mbProductVariant = DB::table('product_variants')
                        ->where('id', $sale_product->product_variant_id)
                        ->where('product_id', $sale_product->product_id)
                        ->first();
                    $qty_limits[] = $mbProductVariant->mb_stock;
                } else {
                    $qty_limits[] = $mbProduct->mb_stock;
                }
            }
        }

        return response()->json(['sale' => $sale, 'qty_limits' => $qty_limits]);
    }

    // Update Sale 
    public function update(Request $request, $saleId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['sale_invoice'];
        if ($request->product_ids == null) {
            return response()->json(['errorMsg' => 'product table is empty']);
        }

        $this->validate($request, [
            'status' => 'required',
            'date' => 'required',
        ]);

        $updateSale = Sale::with([
            'sale_products',
            'sale_products.product',
            'sale_products.variant',
            'sale_products.product.comboProducts'
        ])->where('id', $saleId)->first();

        // Update customer total sale due
        if ($request->status == 1) {
            $customer = Customer::where('id', $updateSale->customer_id)->first();
            if ($customer) {
                $presentDue = $request->total_payable_amount - $updateSale->paid - $updateSale->sale_return_amount;
                $previousDue = $updateSale->due;
                $customerDue = $presentDue - $previousDue;
                $customer->total_sale_due = $customer->total_sale_due + $customerDue;
                $customer->total_sale = $customer->total_sale - $updateSale->total_payable_amount;
                $customer->total_sale =  $customer->total_sale + $request->total_payable_amount;
                $customer->save();
            }
        }

        // update product quantity for adjustment
        foreach ($updateSale->sale_products as $sale_product) {
            $sale_product->delete_in_update = 1;
            $sale_product->save();
            if ($updateSale->status == 1) {
                if ($sale_product->product->type == 1) {
                    $sale_product->product->quantity += $sale_product->quantity;
                    $sale_product->product->number_of_sale -= $sale_product->quantity;
                    $sale_product->product->save();
                    if ($sale_product->product_variant_id) {
                        $sale_product->variant->variant_quantity += $sale_product->quantity;
                        $sale_product->variant->number_of_sale -= $sale_product->quantity;
                        $sale_product->variant->save();
                    }

                    if ($updateSale->branch_id) {
                        $productBranch = ProductBranch::where('branch_id', $updateSale->branch_id)
                            ->where('product_id', $sale_product->product_id)
                            ->first();
                        $productBranch->product_quantity = $productBranch->product_quantity + $sale_product->quantity;
                        $productBranch->save();
                        if ($sale_product->product_variant_id) {
                            $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                                ->where('product_id', $sale_product->product_id)
                                ->where('product_variant_id', $sale_product->product_variant_id)
                                ->first();
                            $productBranchVariant->variant_quantity = $productBranchVariant->variant_quantity + $sale_product->quantity;
                            $productBranchVariant->save();
                        }
                    } else {
                        $mbProduct = Product::where('id', $sale_product->product_id)
                            ->first();
                        $mbProduct->mb_stock += $sale_product->quantity;
                        $mbProduct->save();
                        if ($sale_product->product_variant_id) {
                            $mbProductVariant = ProductVariant::where('id', $sale_product->product_variant_id)
                                ->where('product_id', $sale_product->product_id)->first();
                            $mbProductVariant->mb_stock += $sale_product->quantity;
                            $mbProductVariant->save();
                        }
                    }
                }
            }
        }

        // generate invoice ID
        $i = 6;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        $updateSale->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : 'SI') . date('ymd') . $invoiceId;
        $updateSale->status = $request->status;
        $updateSale->pay_term = $request->pay_term;
        $updateSale->date = $request->date;
        $updateSale->pay_term_number = $request->pay_term_number;
        $updateSale->total_item = $request->total_item;
        $updateSale->net_total_amount = $request->net_total_amount;
        $updateSale->order_discount_type = $request->order_discount_type;
        $updateSale->order_discount = $request->order_discount;
        $updateSale->order_discount_amount = $request->order_discount_amount;
        $updateSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
        $updateSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $updateSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $updateSale->total_payable_amount = $request->total_payable_amount;
        $updateSale->due = $request->total_payable_amount - $updateSale->paid - $updateSale->sale_return_amount;
        $updateSale->shipment_details = $request->shipment_details;
        $updateSale->shipment_address = $request->shipment_address;
        $updateSale->shipment_status = $request->shipment_status;
        $updateSale->delivered_to = $request->delivered_to;
        $updateSale->sale_note = $request->sale_note;
        $updateSale->save();

        // update product quantity
        $quantities = $request->quantities;
        $units = $request->units;
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $unit_discount_types = $request->unit_discount_types;
        $unit_discounts = $request->unit_discounts;
        $unit_discount_amounts = $request->unit_discount_amounts;
        $unit_tax_percents = $request->unit_tax_percents;
        $unit_tax_amounts = $request->unit_tax_amounts;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $unit_prices_exc_tax = $request->unit_prices_exc_tax;
        $unit_prices = $request->unit_prices;
        $subtotals = $request->subtotals;
        $descriptions = $request->descriptions;
        $index = 0;

        // Update branch product stock
        if ($request->status == 1) {
            $this->saleUtil->updateProductBranchStock($request, auth()->user()->branch_id);
        }

        // Update sale product rows
        $__index = 0;
        foreach ($product_ids as $product_id) {
            $variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $saleProduct = SaleProduct::where('sale_id', $updateSale->id)->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();

            if ($saleProduct) {
                $saleProduct->quantity = $quantities[$__index];
                $saleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$__index];
                $saleProduct->unit_price_exc_tax = $unit_prices_exc_tax[$__index];
                $saleProduct->unit_price_inc_tax = $unit_prices[$__index];
                $saleProduct->unit_discount_type = $unit_discount_types[$__index];
                $saleProduct->unit_discount = $unit_discounts[$__index];
                $saleProduct->unit_discount_amount = $unit_discount_amounts[$__index];
                $saleProduct->unit_tax_percent = $unit_tax_percents[$__index];
                $saleProduct->unit_tax_amount = $unit_tax_amounts[$__index];
                $saleProduct->unit = $units[$__index];
                $saleProduct->subtotal = $subtotals[$__index];
                $saleProduct->description = $descriptions[$__index] ? $descriptions[$__index] : NULL;
                $saleProduct->delete_in_update = 0;
                $saleProduct->save();
            } else {
                $addSaleProduct = new SaleProduct();
                $addSaleProduct->sale_id = $updateSale->id;
                $addSaleProduct->product_id = $product_id;
                $addSaleProduct->product_variant_id = $variant_ids[$__index] != 'noid' ? $variant_ids[$__index] : NULL;
                $addSaleProduct->quantity = $quantities[$__index];
                $addSaleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$__index];
                $addSaleProduct->unit_price_exc_tax = $unit_prices_exc_tax[$__index];
                $addSaleProduct->unit_price_inc_tax = $unit_prices[$__index];
                $addSaleProduct->unit_discount_type = $unit_discount_types[$__index];
                $addSaleProduct->unit_discount = $unit_discounts[$__index];
                $addSaleProduct->unit_discount_amount = $unit_discount_amounts[$__index];
                $addSaleProduct->unit_tax_percent = $unit_tax_percents[$__index];
                $addSaleProduct->unit_tax_amount = $unit_tax_amounts[$__index];
                $addSaleProduct->unit = $units[$__index];
                $addSaleProduct->subtotal = $subtotals[$__index];
                $addSaleProduct->description = $descriptions[$__index] ? $descriptions[$__index] : NULL;
                $addSaleProduct->save();
            }
            $__index++;
        }

        $deleteNotFoundSaleProducts = SaleProduct::where('sale_id', $updateSale->id)
            ->where('delete_in_update', 1)->get();
        foreach ($deleteNotFoundSaleProducts as $deleteNotFoundSaleProduct) {
            $deleteNotFoundSaleProduct->delete();
        }

        if ($request->status == 1) {
            session()->flash('successMsg', 'Sale updated successfully');
            return response()->json(['successMsg' => 'Sale updated successfully']);
        } elseif ($request->status == 2) {
            session()->flash('successMsg', 'Sale draft updated successfully');
            return response()->json(['successMsg' => 'Sale draft updated successfully']);
        } elseif ($request->status == 4) {
            session()->flash('successMsg', 'Sale quotation updated successfully');
            return response()->json(['successMsg' => 'Sale quotation updated successfully']);
        }
    }

    // Delete Sale
    public function delete(Request $request, $saleId)
    {
        $deleteSale = Sale::with([
            'sale_products',
            'sale_products.product',
            'sale_products.variant',
            'sale_products.product.comboProducts'
        ])->where('id', $saleId)->first();

        if ($deleteSale->status == 1) {
            $customer = Customer::where('id', $deleteSale->customer_id)->first();
            if ($customer) {
                $customer->total_sale_due = $customer->total_sale_due - ($deleteSale->due > 0 ? $deleteSale->due : 0);
                $customer->total_sale_return_due = $customer->total_sale_return_due - $deleteSale->sale_return_due;
                $customer->save();
            }
        }

        // Add product quantity for adjustment
        if ($deleteSale->status == 1) {
            foreach ($deleteSale->sale_products as $sale_product) {
                if ($sale_product->product->type == 1) {
                    $sale_product->product->quantity += $sale_product->quantity;
                    $sale_product->product->number_of_sale -= $sale_product->quantity;
                    $sale_product->product->save();
                    if ($sale_product->product_variant_id) {
                        $sale_product->variant->variant_quantity += $sale_product->quantity;
                        $sale_product->variant->number_of_sale -= $sale_product->quantity;
                        $sale_product->variant->save();
                    }

                    if ($deleteSale->branch_id) {
                        $productBranch = ProductBranch::where('branch_id', $deleteSale->branch_id)
                            ->where('product_id', $sale_product->product_id)
                            ->first();
                        $productBranch->product_quantity += $sale_product->quantity;
                        $productBranch->save();
                        if ($sale_product->product_variant_id) {
                            $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                                ->where('product_id', $sale_product->product_id)
                                ->where('product_variant_id', $sale_product->product_variant_id)
                                ->first();

                            $productBranchVariant->variant_quantity += $sale_product->quantity;
                            $productBranchVariant->save();
                        }
                    } else {
                        $product = Product::where('id', $sale_product->product_id)
                            ->first();
                        $product->mb_stock += $sale_product->quantity;
                        $product->save();

                        if ($sale_product->product_variant_id) {
                            $productVariant = ProductVariant::where('id', $sale_product->product_variant_id)
                                ->where('id', $sale_product->product_id)
                                ->first();
                            $productVariant->mb_stock += $sale_product->quantity;
                            $productVariant->save();
                        }
                    }
                }
            }
        }

        $deleteSale->delete();
        return response()->json('Sale deleted successfully');
    }

    // Sale Packing Slip
    public function packingSlip($saleId)
    {
        $sale = Sale::with(['branch', 'customer'])->where('id', $saleId)->first();
        return view('sales.ajax_view.print_packing_slip', compact('sale'));
    }

    // Shipments View
    public function shipments(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $sales = '';
            $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id')
                ->leftJoin('admin_and_users', 'sales.admin_id', 'admin_and_users.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('sales.branch_id', NULL);
                } else {
                    $query->where('sales.branch_id', $request->branch_id);
                }
            }

            if ($request->customer_id) {
                if ($request->customer_id == 'NULL') {
                    $query->where('sales.customer_id', NULL);
                } else {
                    $query->where('sales.customer_id', $request->customer_id);
                }
            }

            if ($request->payment_status) {
                if ($request->payment_status == 1) {
                    $query->where('sales.due', '=', 0);
                } else {
                    $query->where('sales.due', '>', 0);
                }
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
                $query->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $sales = $query->select(
                    'sales.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'customers.name as customer_name',
                    'admin_and_users.prefix as cr_prefix',
                    'admin_and_users.name as cr_name',
                    'admin_and_users.last_name as cr_last_name',
                )->where('sales.created_by', 1)->orderBy('id', 'desc')->where('sales.status', 1)
                    ->where('shipment_status', '!=', 'NULL')
                    ->orderBy('id', 'desc')
                    ->get();
            } else {
                $sales = $query->select(
                    'sales.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'customers.name as customer_name',
                    'admin_and_users.prefix as cr_prefix',
                    'admin_and_users.name as cr_name',
                    'admin_and_users.last_name as cr_last_name',
                )->where('sales.created_by', 1)->where('branch_id', auth()->user()->branch_id)
                    ->where('sales.status', 1)
                    ->where('shipment_status', '!=', 'NULL')
                    ->orderBy('id', 'desc')
                    ->get();
            }

            return DataTables::of($sales)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.show', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i> View</a>';
                    $html .= '<a class="dropdown-item" id="edit_shipment" href="' . route('sales.shipment.edit', [$row->id]) . '"><i class="fas fa-truck mr-1 text-primary"></i> Edit shipment</a>';
                    $html .= '<a class="dropdown-item" id="print_packing_slip" href="' . route('sales.packing.slip', [$row->id]) . '"><i class="fas fa-file-alt mr-1 text-primary"></i> Packing Slip </a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('customer',  function ($row) {
                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('created_by',  function ($row) {
                    return $row->cr_prefix . ' ' . $row->cr_name . ' ' . $row->cr_last_name;
                })
                ->editColumn('shipment_status',  function ($row) {
                    $html = "";
                    if ($row->shipment_status == 1) {
                        $html .= '<span class="text-primary"><b>Ordered</b></span>';
                    } elseif ($row->shipment_status == 2) {
                        $html .= '<span class="text-secondary"><b>Packed</b></span>';
                    } elseif ($row->shipment_status == 3) {
                        $html .= '<span class="text-warning"><b>Shipped</b></span>';
                    } elseif ($row->shipment_status == 4) {
                        $html .= '<span class="text-success"><b>Delivered</b></span>';
                    } elseif ($row->shipment_status == 5) {
                        $html .= '<span class="text-danger"><b>Cancelled</b></span>';
                    }
                    return $html;
                })
                ->editColumn('paid_status', function ($row) {
                    $payable = $row->total_payable_amount - $row->sale_return_amount;
                    $html = '';
                    if ($row->due <= 0) {
                        $html .= '<span class="text-success"><b>Paid</b></span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {
                        $html .= '<span class="text-primary"><b>Partial</b></span>';
                    } elseif ($payable == $row->due) {
                        $html .= '<span class="text-danger"><b>Due</b></span>';
                    }
                    return $html;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('sales.show', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row text-start')
                ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'shipment_status', 'paid_status'])
                ->make(true);
        }
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('sales.shipments', compact('branches'));
    }

    // Update shipment
    public function updateShipment(Request $request, $saleId)
    {
        $sale = Sale::where('id', $saleId)->first();
        $sale->shipment_details = $request->shipment_details;
        $sale->shipment_address = $request->shipment_address;
        $sale->shipment_status = $request->shipment_status;
        $sale->delivered_to = $request->delivered_to;
        $sale->save();
        return response()->json('Successfully shipment is updated.');
    }

    // Get all customers requested by ajax
    public function getAllCustomer()
    {
        $customers = Customer::select('id', 'name',  'pay_term', 'pay_term_number', 'phone', 'total_sale_due')
            ->where('is_walk_in_customer', 0)
            ->orderBy('id', 'desc')
            ->get();
        return response()->json($customers);
    }

    // Get customer info
    public function customerInfo($customerId)
    {
        $customer = DB::table('customers')->where('id', $customerId)
            ->select('pay_term', 'pay_term_number', 'total_sale_due', 'point')->first();
        return response()->json($customer);
    }

    // Get all user requested by ajax
    public function getAllUser()
    {
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $users = AdminAndUser::with(['role'])
                ->select(['id', 'prefix',  'name', 'last_name', 'role_type', 'role_id', 'email'])->where('allow_login', 1)->get();
            return response()->json($users);
        } else {
            $users = AdminAndUser::with(['role'])->where('branch_id', auth()->user()->branch_id)
                ->select(['id', 'prefix',  'name', 'last_name', 'role_type', 'role_id', 'email'])
                ->where('allow_login', 1)
                ->get();
            return response()->json($users);
        }
    }

    // Search product by code
    public function searchProduct($product_code)
    {
        $product_code = (string)$product_code;
        $branch_id = auth()->user()->branch_id;
        $product = Product::with(['product_variants', 'tax', 'unit'])
            ->where('product_code', $product_code)
            ->select([
                'id',
                'name',
                'type',
                'product_code',
                'product_price',
                'profit',
                'product_cost_with_tax',
                'thumbnail_photo',
                'unit_id',
                'tax_id',
                'tax_type',
                'is_show_emi_on_pos',
                'mb_stock',
            ])->first();

        if ($product) {
            if ($branch_id) {
                $productBranch = DB::table('product_branches')
                    ->where('branch_id', $branch_id)
                    ->where('product_id', $product->id)
                    ->select('product_quantity')
                    ->first();
                if ($productBranch) {
                    if ($product->type == 2) {
                        return response()->json(['errorMsg' => 'Combo product is not sellable in this demo']);
                    } else {
                        if ($productBranch->product_quantity > 0) {
                            return response()->json(
                                [
                                    'product' => $product,
                                    'qty_limit' => $productBranch->product_quantity
                                ]
                            );
                        } else {
                            return response()->json(['errorMsg' => 'Stock is out of this product of this branch/shop']);
                        }
                    }
                } else {
                    return response()->json(['errorMsg' => 'This product is not available in this branch/shop. ']);
                }
            } else {
                if ($product->type === 2) {
                    //$this->saleUtil->checkComboProductStock();
                    return response()->json(['errorMsg' => 'Combo product is not sellable in this demo']);
                } else {
                    if ($product->mb_stock > 0) {
                        return response()->json(
                            [
                                'product' => $product,
                                'qty_limit' => $product->mb_stock
                            ]
                        );
                    } else {
                        return response()->json(['errorMsg' => 'Stock is not available of this product in this branch/shop 87819368']);
                    }
                }
            }
        } else {
            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')
                ->where('variant_code', $product_code)
                ->select([
                    'id', 'product_id', 'variant_name', 'variant_code', 'variant_quantity', 'variant_cost', 'variant_cost_with_tax', 'variant_profit', 'variant_price', 'mb_stock'
                ])->first();
            if ($variant_product) {
                if ($branch_id) {
                    if ($variant_product) {
                        $productBranch = DB::table('product_branches')
                            ->where('branch_id', $branch_id)
                            ->where('product_id', $variant_product->product_id)
                            ->first();

                        if (is_null($productBranch)) {
                            return response()->json(['errorMsg' => 'This product is not available in this shop']);
                        }

                        $productBranchVariant = DB::table('product_branch_variants')
                            ->where('product_branch_id', $productBranch->id)
                            ->where('product_id', $variant_product->product_id)
                            ->where('product_variant_id', $variant_product->id)
                            ->select('variant_quantity')
                            ->first();

                        if (is_null($productBranchVariant)) {
                            return response()->json(['errorMsg' => 'This variant is not available in this shop']);
                        }

                        if ($productBranch && $productBranchVariant) {
                            if ($productBranchVariant->variant_quantity > 0) {
                                return response()->json([
                                    'variant_product' => $variant_product,
                                    'qty_limit' => $productBranchVariant->variant_quantity
                                ]);
                            } else {
                                return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this branch']);
                            }
                        } else {
                            return response()->json(['errorMsg' => 'This product is not available in this branch.']);
                        }
                    }
                } else {
                    if ($variant_product->mb_stock > 0) {
                        return response()->json(['variant_product' => $product, 'qty_limit' => $variant_product->mb_stock]);
                    } else {
                        return response()->json(['errorMsg' => 'Stock is not available of this product in this branch/shop']);
                    }
                }
            }
        }

        return $this->nameSearchUtil->nameSearching($product_code);
    }

    // Check Branch product variant Stock 
    public function checkBranchProductVariant($product_id, $variant_id)
    {
        $branch_id = auth()->user()->branch_id;
        if ($branch_id) {
            $productBranch = DB::table('product_branches')->where('branch_id', $branch_id)->where('product_id', $product_id)->first();
            if ($productBranch) {
                $productBranchVariant = DB::table('product_branch_variants')->where('product_branch_id', $productBranch->id)
                    ->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)->first();
                if ($productBranchVariant) {
                    if ($productBranchVariant->variant_quantity > 0) {
                        return response()->json($productBranchVariant->variant_quantity);
                    } else {
                        return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this shop']);
                    }
                } else {
                    return response()->json(['errorMsg' => 'This variant is not available in this shop.']);
                }
            } else {
                return response()->json(['errorMsg' => 'This product is not available in this shop.']);
            }
        } else {
            $mb_variant_stock = DB::table('product_variants')
                ->where('id', $variant_id)
                ->where('product_id', $product_id)
                ->first();

            if ($mb_variant_stock->mb_stock > 0) {
                return response()->json($mb_variant_stock->mb_stock);
            } else {
                return response()->json(['errorMsg' => 'Stock is not available of this product(variant) in this branch/shop']);
            }
        }
    }

    // Check Branch Single product Stock
    public function checkBranchSingleProductStock($product_id)
    {
        $branch_id = auth()->user()->branch_id;
        if ($branch_id) {
            $productBranch = DB::table('product_branches')->where('product_id', $product_id)->where('branch_id', $branch_id)->first();
            if ($productBranch) {
                if ($productBranch->product_quantity > 0) {
                    return response()->json($productBranch->product_quantity);
                } else {
                    return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this shop/branch']);
                }
            } else {
                return response()->json(['errorMsg' => 'This product is not available in this shop/branch.']);
            }
        } else {
            $mb_product_stock = DB::table('products')
                ->where('id', $product_id)
                ->first();

            if ($mb_product_stock->mb_stock > 0) {
                return response()->json($mb_product_stock->mb_stock);
            } else {
                return response()->json(['errorMsg' => 'Stock is not available of this product(variant) in this branch/shop']);
            }
        }
    }

    public function editShipment($saleId)
    {
        $sale = Sale::where('id', $saleId)->first();
        return view('sales.ajax_view.edit_shipment', compact('sale'));
    }

    public function viewPayment($saleId)
    {
        $sale = Sale::with(['customer', 'branch', 'sale_payments'])->where('id', $saleId)->first();
        return view('sales.ajax_view.payment_view', compact('sale'));
    }

    // Show payment modal
    public function paymentModal($saleId)
    {
        $accounts = Account::orderBy('id', 'DESC')->where('status', 1)->get();
        $sale = Sale::with('branch', 'customer')->where('id', $saleId)->first();
        return view('sales.ajax_view.add_payment', compact('sale', 'accounts'));
    }

    public function paymentAdd(Request $request, $saleId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['sale_payment'];

        $sale = Sale::where('id', $saleId)->first();
        //Update Customer due 
        $customer = Customer::where('id', $sale->customer_id)->first();
        if ($customer) {
            $customer->total_paid = $customer->total_paid + $request->amount;
            $customer->total_sale_due = $customer->total_sale_due - $request->amount;
            $customer->save();
        }

        // Update sale
        $sale->paid = $sale->paid + $request->amount;
        $sale->due = $sale->due - $request->amount;
        $sale->save();

        // generate invoice ID
        $i = 5;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        // Add sale payment
        $this->saleUtil->addPayment($paymentInvoicePrefix, $request, $request->amount, $invoiceId, $saleId);
        return response()->json('Payment added successfully.');
    }

    // Show payment modal
    public function paymentEdit($paymentId)
    {
        $accounts =  Account::orderBy('id', 'DESC')->where('status', 1)->get();
        $payment = SalePayment::with('sale', 'sale.customer', 'sale.branch')->where('id', $paymentId)->first();
        return view('sales.ajax_view.edit_payment', compact('payment', 'accounts'));
    }

    // Payment update
    public function paymentUpdate(Request $request, $paymentId)
    {
        $updateSalePayment = SalePayment::with(
            'account',
            'customer',
            'sale',
            'cashFlow'
        )->where('id', $paymentId)->first();

        //Update Supplier due 
        if ($updateSalePayment->customer) {
            $updateSalePayment->customer->total_paid = $updateSalePayment->customer->total_paid - $updateSalePayment->paid_amount;
            $updateSalePayment->customer->total_paid = $updateSalePayment->customer->total_paid + $request->amount;
            $updateSalePayment->customer->total_sale_due = $updateSalePayment->customer->total_sale_due + $updateSalePayment->paid_amount;
            $updateSalePayment->customer->total_sale_due = $updateSalePayment->customer->total_sale_due - $request->amount;
            $updateSalePayment->customer->save();
        }

        // Update sale 
        $updateSalePayment->sale->paid = $updateSalePayment->sale->paid - $updateSalePayment->paid_amount;
        $updateSalePayment->sale->due = $updateSalePayment->sale->due + $updateSalePayment->paid_amount;
        $updateSalePayment->sale->paid = $updateSalePayment->sale->paid + $request->amount;
        $updateSalePayment->sale->due = $updateSalePayment->sale->due - $request->amount;
        $updateSalePayment->sale->save();

        // Update previous account and delete previous cashflow.
        if ($updateSalePayment->account) {
            $updateSalePayment->account->credit = $updateSalePayment->account->credit - $updateSalePayment->paid_amount;
            $updateSalePayment->account->balance = $updateSalePayment->account->balance - $updateSalePayment->paid_amount;
            $updateSalePayment->account->save();
            //$updateSalePayment->cashFlow->delete();
        }

        // update sale payment
        $updateSalePayment->account_id = $request->account_id;
        $updateSalePayment->pay_mode = $request->payment_method;
        $updateSalePayment->paid_amount = $request->amount;
        $updateSalePayment->date = $request->date;
        $updateSalePayment->report_date = date('Y-m-d', strtotime($request->date));
        $updateSalePayment->month = date('F');
        $updateSalePayment->year = date('Y');
        $updateSalePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $updateSalePayment->card_no = $request->card_no;
            $updateSalePayment->card_holder = $request->card_holder_name;
            $updateSalePayment->card_transaction_no = $request->card_transaction_no;
            $updateSalePayment->card_type = $request->card_type;
            $updateSalePayment->card_month = $request->month;
            $updateSalePayment->card_year = $request->year;
            $updateSalePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $updateSalePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $updateSalePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $updateSalePayment->transaction_no = $request->transaction_no;
        }

        if ($request->hasFile('attachment')) {
            if ($updateSalePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $updateSalePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $updateSalePayment->attachment));
                }
            }
            $salePaymentAttachment = $request->file('attachment');
            $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
            $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
            $updateSalePayment->attachment = $salePaymentAttachmentName;
        }
        $updateSalePayment->save();

        if ($request->account_id) {
            // update account
            $account = Account::where('id', $request->account_id)->first();
            $account->credit = $account->credit + $request->amount;
            $account->balance = $account->balance + $request->amount;
            $account->save();

            // Add or update cash flow
            $cashFlow = CashFlow::where('account_id', $request->account_id)->where('sale_payment_id', $updateSalePayment->id)->first();
            if ($cashFlow) {
                $cashFlow->credit = $request->amount;
                $cashFlow->balance = $account->balance;
                $cashFlow->date = $request->date;
                $cashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $cashFlow->month = date('F');
                $cashFlow->year = date('Y');
                $cashFlow->save();
            } else {
                if ($updateSalePayment->cashFlow) {
                    $updateSalePayment->cashFlow->delete();
                }

                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->credit = $request->amount;
                $addCashFlow->balance = $account->balance;
                $addCashFlow->sale_payment_id = $updateSalePayment->id;
                $addCashFlow->transaction_type = 2;
                $addCashFlow->cash_type = 2;
                $addCashFlow->date = $request->date;
                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
            }
        }
        return response()->json('Payment updated successfully.');
    }

    // Show payment modal
    public function returnPaymentModal($saleId)
    {
        $accounts = Account::orderBy('id', 'DESC')->where('status', 1)->get();
        $sale = Sale::with('branch', 'customer')->where('id', $saleId)->first();
        return view('sales.ajax_view.add_return_payment', compact('sale', 'accounts'));
    }

    public function returnPaymentAdd(Request $request, $saleId)
    {
        $sale = Sale::where('id', $saleId)->first();
        //Update Supplier due 
        $customer = Customer::where('id', $sale->customer_id)->first();
        if ($customer) {
            $customer->total_sale_return_due = $customer->total_sale_return_due - $request->amount;
            $customer->save();
        }

        // Update sale
        $sale->sale_return_due = $sale->sale_return_due - $request->amount;
        $sale->save();

        // update sale return
        $sale->sale_return->total_return_due_pay = $sale->sale_return->total_return_due_pay + $request->amount;
        $sale->sale_return->total_return_due = $sale->sale_return->total_return_due - $request->amount;
        $sale->sale_return->save();

        // generate invoice ID
        $i = 5;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }
        // Add sale payment
        $addSalePayment = new SalePayment();
        $addSalePayment->invoice_id = 'SRPI' . date('dmy') . $invoiceId;
        $addSalePayment->sale_id = $sale->id;
        $addSalePayment->customer_id = $sale->customer_id ? $sale->customer_id : NULL;
        $addSalePayment->account_id = $request->account_id;
        $addSalePayment->pay_mode = $request->payment_method;
        $addSalePayment->payment_type = 2;
        $addSalePayment->paid_amount = $request->amount;
        $addSalePayment->date = $request->date;
        $addSalePayment->time = date('h:i:s a');
        $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
        $addSalePayment->month = date('F');
        $addSalePayment->year = date('Y');
        $addSalePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $addSalePayment->card_no = $request->card_no;
            $addSalePayment->card_holder = $request->card_holder_name;
            $addSalePayment->card_transaction_no = $request->card_transaction_no;
            $addSalePayment->card_type = $request->card_type;
            $addSalePayment->card_month = $request->month;
            $addSalePayment->card_year = $request->year;
            $addSalePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $addSalePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $addSalePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $addSalePayment->transaction_no = $request->transaction_no;
        }
        $addSalePayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {
            $salePaymentAttachment = $request->file('attachment');
            $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
            $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
            $addSalePayment->attachment = $salePaymentAttachmentName;
        }
        $addSalePayment->save();

        if ($request->account_id) {
            // update account
            $account = Account::where('id', $request->account_id)->first();
            $account->debit = $account->debit + $request->amount;
            $account->balance = $account->balance - $request->amount;
            $account->save();

            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->debit = $request->amount;
            $addCashFlow->balance = $account->balance;
            $addCashFlow->sale_payment_id = $addSalePayment->id;
            $addCashFlow->transaction_type = 2;
            $addCashFlow->cash_type = 1;
            $addCashFlow->date = $request->date;
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
        }

        if ($customer) {
            $addCustomerLedger = new CustomerLedger();
            $addCustomerLedger->customer_id = $customer->id;
            $addCustomerLedger->sale_payment_id = $addSalePayment->id;
            $addCustomerLedger->row_type = 2;
            $addCustomerLedger->save();
        }

        return response()->json('Return amount paid successfully.');
    }

    public function returnPaymentEdit($paymentId)
    {
        $accounts = Account::orderBy('id', 'DESC')->where('status', 1)->get();
        $payment = SalePayment::with('sale', 'sale.customer', 'sale.branch')->where('id', $paymentId)->first();
        return view('sales.ajax_view.edit_return_payment', compact('payment', 'accounts'));
    }

    public function returnPaymentUpdate(Request $request, $paymentId)
    {
        $updateSalePayment = SalePayment::with(
            'account',
            'customer',
            'sale',
            'sale.sale_return',
            'cashFlow'
        )->where('id', $paymentId)->first();
        //Update Customer due 
        if ($updateSalePayment->customer) {
            $updateSalePayment->customer->total_sale_return_due = $updateSalePayment->customer->total_sale_return_due + $updateSalePayment->paid_amount;
            $updateSalePayment->customer->total_sale_return_due = $updateSalePayment->customer->total_sale_return_due - $request->amount;
            $updateSalePayment->customer->save();
        }

        // Update sale 
        $updateSalePayment->sale->sale_return_due = $updateSalePayment->sale->sale_return_due - $updateSalePayment->paid_amount;
        $updateSalePayment->sale->sale_return_due = $updateSalePayment->sale->sale_return_due - $request->amount;
        $updateSalePayment->sale->save();

        // Update sale return
        $updateSalePayment->sale->sale_return->total_return_due = $updateSalePayment->sale->sale_return->total_return_due + $updateSalePayment->paid_amount;
        $updateSalePayment->sale->sale_return->total_return_due = $updateSalePayment->sale->sale_return->total_return_due - $request->amount;
        $updateSalePayment->sale->sale_return->total_return_due_pay = $updateSalePayment->sale->sale_return->total_return_due_pay + $updateSalePayment->paid_amount;
        $updateSalePayment->sale->sale_return->total_return_due_pay = $updateSalePayment->sale->sale_return->total_return_due_pay - $request->amount;
        $updateSalePayment->sale->sale_return->save();

        // Update previous account and delete previous cashflow.
        if ($updateSalePayment->account) {
            $updateSalePayment->account->debit = $updateSalePayment->account->debit - $updateSalePayment->paid_amount;
            $updateSalePayment->account->balance = $updateSalePayment->account->balance + $updateSalePayment->paid_amount;
            $updateSalePayment->account->save();
            //$updateSalePayment->cashFlow->delete();
        }

        // update sale payment
        $updateSalePayment->account_id = $request->account_id;
        $updateSalePayment->pay_mode = $request->payment_method;
        $updateSalePayment->paid_amount = $request->amount;
        $updateSalePayment->date = $request->date;
        $updateSalePayment->report_date = date('Y-m-d', strtotime($request->date));
        $updateSalePayment->month = date('F');
        $updateSalePayment->year = date('Y');
        $updateSalePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $updateSalePayment->card_no = $request->card_no;
            $updateSalePayment->card_holder = $request->card_holder_name;
            $updateSalePayment->card_transaction_no = $request->card_transaction_no;
            $updateSalePayment->card_type = $request->card_type;
            $updateSalePayment->card_month = $request->month;
            $updateSalePayment->card_year = $request->year;
            $updateSalePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $updateSalePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $updateSalePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $updateSalePayment->transaction_no = $request->transaction_no;
        }

        if ($request->hasFile('attachment')) {
            if ($updateSalePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $updateSalePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $updateSalePayment->attachment));
                }
            }
            $salePaymentAttachment = $request->file('attachment');
            $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
            $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
            $updateSalePayment->attachment = $salePaymentAttachmentName;
        }
        $updateSalePayment->save();


        if ($request->account_id) {
            // update account
            $account = Account::where('id', $request->account_id)->first();
            $account->debit = $account->debit + $request->amount;
            $account->balance = $account->balance - $request->amount;
            $account->save();

            // Add or update cash flow
            $cashFlow = CashFlow::where('account_id', $request->account_id)->where('sale_payment_id', $updateSalePayment->id)->first();
            if ($cashFlow) {
                $cashFlow->debit = $request->amount;
                $cashFlow->balance = $account->balance;
                $cashFlow->date = $request->date;
                $cashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $cashFlow->month = date('F');
                $cashFlow->year = date('Y');
                $cashFlow->save();
            } else {
                if ($updateSalePayment->cashFlow) {
                    $updateSalePayment->cashFlow->delete();
                }

                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->debit = $request->amount;
                $addCashFlow->balance = $account->balance;
                $addCashFlow->sale_payment_id = $updateSalePayment->id;
                $addCashFlow->transaction_type = 2;
                $addCashFlow->cash_type = 1;
                $addCashFlow->date = $request->date;
                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
            }
        } else {
            if ($updateSalePayment->cashFlow) {
                $updateSalePayment->cashFlow->delete();
            }
        }
        return response()->json('Payment is updated successfully.');
    }

    // payment details
    public function paymentDetails($paymentId)
    {
        $payment = SalePayment::with('sale', 'sale.branch', 'sale.customer')->where('id', $paymentId)->first();
        return view('sales.ajax_view.payment_details', compact('payment'));
    }

    // Delete sale payment
    public function paymentDelete(Request $request, $paymentId)
    {
        $deleteSalePayment = SalePayment::with('account', 'customer', 'sale', 'sale.sale_return', 'cashFlow')
            ->where('id', $paymentId)->first();

        if (!is_null($deleteSalePayment)) {
            //Update customer due 
            if ($deleteSalePayment->payment_type == 1) {
                if ($deleteSalePayment->customer) {
                    $deleteSalePayment->customer->total_sale_due = $deleteSalePayment->customer->total_sale_due + $deleteSalePayment->paid_amount;
                    $deleteSalePayment->customer->save();
                }

                // Update sale 
                $deleteSalePayment->sale->paid = $deleteSalePayment->sale->paid - $deleteSalePayment->paid_amount;
                $deleteSalePayment->sale->due = $deleteSalePayment->sale->due + $deleteSalePayment->paid_amount;
                $deleteSalePayment->sale->save();

                // Update previous account and delete previous cashflow.
                if ($deleteSalePayment->account) {
                    $deleteSalePayment->account->credit = $deleteSalePayment->account->credit - $deleteSalePayment->paid_amount;
                    $deleteSalePayment->account->balance = $deleteSalePayment->account->balance - $deleteSalePayment->paid_amount;
                    $deleteSalePayment->account->save();
                    $deleteSalePayment->cashFlow->delete();
                }

                if ($deleteSalePayment->attachment != null) {
                    if (file_exists(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment))) {
                        unlink(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment));
                    }
                }

                $deleteSalePayment->delete();
            } elseif ($deleteSalePayment->payment_type == 2) {
                if ($deleteSalePayment->customer) {
                    $deleteSalePayment->customer->total_sale_return_due = $deleteSalePayment->customer->total_sale_return_due + $deleteSalePayment->paid_amount;
                    $deleteSalePayment->customer->save();
                }

                // Update sale 
                $deleteSalePayment->sale->sale_return_due = $deleteSalePayment->sale->sale_return_due + $deleteSalePayment->paid_amount;
                $deleteSalePayment->sale->save();

                // Update sale return
                $deleteSalePayment->sale->sale_return->total_return_due = $deleteSalePayment->sale->sale_return->total_return_due + $deleteSalePayment->paid_amount;
                $deleteSalePayment->sale->sale_return->total_return_due_pay = $deleteSalePayment->sale->sale_return->total_return_due_pay - $deleteSalePayment->paid_amount;
                $deleteSalePayment->sale->sale_return->save();

                // Update previous account and delete previous cashflow.
                if ($deleteSalePayment->account) {
                    $deleteSalePayment->account->debit = $deleteSalePayment->account->debit - $deleteSalePayment->paid_amount;
                    $deleteSalePayment->account->balance = $deleteSalePayment->account->balance + $deleteSalePayment->paid_amount;
                    $deleteSalePayment->account->save();
                    $deleteSalePayment->cashFlow->delete();
                }

                if ($deleteSalePayment->attachment != null) {
                    if (file_exists(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment))) {
                        unlink(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment));
                    }
                }

                $deleteSalePayment->delete();
            }
        }
        return response()->json('Payment deleted successfully.');
    }

    // Add product modal view with data
    public function addProductModalVeiw()
    {
        $units = Unit::select(['id', 'name'])->get();
        $warranties =  Warranty::select(['id', 'name', 'type'])->get();
        $taxes = Tax::select(['id', 'tax_name', 'tax_percent'])->get();
        $categories = Category::where('parent_category_id', NULL)->orderBy('id', 'DESC')->get();
        $brands = $brands = Brand::all();
        return view('sales.ajax_view.add_product_modal_view', compact('units', 'warranties', 'taxes', 'categories', 'brands'));
    }

    public function getAllSubCategory($categoryId)
    {
        $sub_categories = DB::table('categories')->where('parent_category_id', $categoryId)->get();
        return response()->json($sub_categories);
    }

    public function addProduct(Request $request)
    {
        $addProduct = new Product();
        $tax_id = NULL;
        if ($request->tax_id) {
            $tax_id = explode('-', $request->tax_id)[0];
        }

        $this->validate(
            $request,
            [
                'name' => 'required',
                'product_code' => 'required',
                'unit_id' => 'required',
                'product_price' => 'required',
                'product_cost' => 'required',
                'product_cost_with_tax' => 'required',
            ],
            [
                'unit_id.required' => 'Product unit field is required.',
            ]
        );

        $addProduct->type = 1;
        $addProduct->name = $request->name;
        $addProduct->product_code = $request->product_code;
        $addProduct->category_id = $request->category_id;
        $addProduct->parent_category_id = $request->child_category_id;
        $addProduct->brand_id = $request->brand_id;
        $addProduct->unit_id = $request->unit_id;
        $addProduct->product_cost = $request->product_cost;
        $addProduct->profit = $request->profit ? $request->profit : 0.00;
        $addProduct->product_cost_with_tax = $request->product_cost_with_tax;
        $addProduct->product_price = $request->product_price;
        $addProduct->alert_quantity = $request->alert_quantity;
        $addProduct->tax_id = $tax_id;
        $addProduct->tax_type = 1;
        $addProduct->product_details = $request->product_details;
        $addProduct->is_purchased = 1;
        $addProduct->barcode_type = $request->barcode_type;
        $addProduct->warranty_id = $request->warranty_id;
        $addProduct->is_purchased = 1;
        $addProduct->is_show_in_ecom = isset($request->is_show_in_ecom) ? 1 : 0;
        $addProduct->is_show_emi_on_pos = isset($request->is_show_emi_on_pos) ? 1 : 0;
        $addProduct->mb_stock = !$request->branch_id ? $request->quantity : 0;
        $addProduct->save();

        //Add opening stock
        if ($request->branch_id) {
            //Add opening stock
            $addOpeningStock = new ProductOpeningStock();
            $addOpeningStock->branch_id = $request->branch_id;
            $addOpeningStock->product_id  = $addProduct->id;
            $addOpeningStock->unit_cost_exc_tax = $request->unit_cost_exc_tax;
            $addOpeningStock->quantity = $request->quantity;
            $addOpeningStock->subtotal = $request->subtotal;
            $addOpeningStock->save();

            // Add product Branch
            $addProductBranch = new ProductBranch();
            $addProductBranch->branch_id = $request->branch_id;
            $addProductBranch->product_id = $addProduct->id;
            $addProductBranch->product_quantity = $request->quantity;
            $addProductBranch->save();
        }
        return response()->json($addProduct);
    }

    // Get recent added product which has been added from pos
    public function getRecentProduct($product_id)
    {
        $branch_id = auth()->user()->branch_id;
        $product = ProductBranch::with(['product', 'product.tax', 'product.unit'])
            ->where('branch_id', $branch_id)
            ->where('product_id', $product_id)
            ->first();

        if ($branch_id) {
            if ($product->product_quantity > 0) {
                return view('sales.ajax_view.recent_product_view', compact('product'));
            } else {
                return response()->json([
                    'errorMsg' => 'Product is not added in the sale table, cause you did not add any number of opening stock in this branch.'
                ]);
            }
        } else {
            $mb_product = Product::with(['tax', 'unit'])
                ->where('id', $product_id)
                ->first();
            if ($mb_product->mb_stock > 0) {
                return view('sales.ajax_view.recent_product_view', compact('mb_product'));
            } else {
                return response()->json([
                    'errorMsg' => 'Product is not added in the sale table, cause you did not add any number of opening stock in this branch.'
                ]);
            }
        }
    }

    // Get sale for printing
    public function print($saleId)
    {
        $sale = Sale::with([
            'customer',
            'branch',
            'branch.add_sale_invoice_layout',
            'branch.pos_sale_invoice_layout',
            'sale_products',
            'sale_products.product',
            'sale_products.product.warranty',
            'sale_products.variant',
            'admin'
        ])->where('id', $saleId)->first();

        $previous_due = 0;
        $total_payable_amount = $sale->total_payable_amount;
        $paying_amount = $sale->paid;
        $total_due = $sale->due;
        $change_amount = 0;

        if ($sale->status == 1) {
            if ($sale->created_by == 1) {
                return view('sales.save_and_print_template.sale_print', compact(
                    'sale',
                    'previous_due',
                    'total_payable_amount',
                    'paying_amount',
                    'total_due',
                    'change_amount'
                ));
            } else {
                return view('sales.save_and_print_template.pos_sale_print', compact(
                    'sale',
                    'previous_due',
                    'total_payable_amount',
                    'paying_amount',
                    'total_due',
                    'change_amount'
                ));
            }
        } elseif ($sale->status == 2) {
            return view('sales.save_and_print_template.draft_print', compact('sale'));
        } elseif ($sale->status == 4) {
            return view('sales.save_and_print_template.quotation_print', compact('sale'));
        }
    }

    // Get product price group
    public function getProductPriceGroup()
    {
        return DB::table('price_group_products')->get(['id', 'price_group_id', 'product_id', 'variant_id', 'price']);
    }

    // Recent Add sale
    public function recentSale()
    {
        $sales = Sale::with('customer')->where('branch_id', auth()->user()->branch_id)
            ->where('admin_id', auth()->user()->id)
            ->where('status', 1)
            ->where('created_by', 1)
            ->where('is_return_available', 0)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        return view('sales.ajax_view.recent_sale_list', compact('sales'));
    }

    // Get all recent quotations ** requested by ajax **
    public function recentQuotations()
    {
        $quotations = Sale::where('branch_id', auth()->user()->branch_id)
            ->where('admin_id', auth()->user()->id)
            ->where('status', 4)
            ->where('created_by', 1)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        return view('sales.ajax_view.recent_quotation_list', compact('quotations'));
    }

    // Get all recent drafts ** requested by ajax **
    public function recentDrafts()
    {
        $drafts = Sale::with('customer')->where('branch_id', auth()->user()->branch_id)
            ->where('admin_id', auth()->user()->id)
            ->where('status', 2)
            ->where('created_by', 1)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        return view('sales.ajax_view.recent_draft_list', compact('drafts'));
    }
}
