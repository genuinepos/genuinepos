<?php

namespace App\Http\Controllers\Sales\Reports;

use Carbon\Carbon;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\AccountFilterService;

class SalesReturnReportController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('sale_return_statements')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = config('generalSettings');
            $returns = '';

            $query = DB::table('sale_returns')
                ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
                ->leftJoin('accounts as customers', 'sale_returns.customer_account_id', 'customers.id')
                ->leftJoin('branches', 'sale_returns.branch_id', 'branches.id')
                ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

            $this->filter(request: $request, query: $query);

            $returns = $query->select(
                'sale_returns.id',
                'sale_returns.sale_id',
                'sale_returns.branch_id',
                'sale_returns.voucher_no',
                'sale_returns.date',
                'sale_returns.total_qty',
                'sale_returns.net_total_amount',
                'sale_returns.return_discount_type',
                'sale_returns.return_discount_amount',
                'sale_returns.return_tax_percent',
                'sale_returns.return_tax_amount',
                'sale_returns.total_return_amount',
                'sale_returns.paid',
                'sale_returns.due',
                'sales.invoice_id',
                'branches.name as branch_name',
                'branches.area_name as branch_area_name',
                'branches.branch_code',
                'parentBranch.name as parent_branch_name',
                'customers.name as customer_name',
            )->orderBy('sale_returns.date_ts', 'desc');

            return DataTables::of($returns)
                ->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);
                    return date($__date_format, strtotime($row->date));
                })
                ->editColumn('voucher_no', function ($row) {

                    return '<a href="' . route('sales.returns.show', [$row->id]) . '" id="details_btn">' . $row->voucher_no . '</a>';
                })
                ->editColumn('invoice_id', function ($row) {

                    if ($row->sale_id) {

                        return '<a href="' . route('sales.show', [$row->sale_id]) . '" id="details_btn">' . $row->invoice_id . '</a>';
                    }
                })
                ->editColumn('branch', function ($row) use ($generalSettings) {

                    if ($row->branch_id) {

                        if ($row->parent_branch_name) {

                            return $row->parent_branch_name . '(' . $row->area_name . ')';
                        } else {

                            return $row->branch_name . '(' . $row->area_name . ')';
                        }
                    } else {

                        return $generalSettings['business__shop_name'];
                    }
                })
                ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

                ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')

                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . \App\Utils\Converter::format_in_bdt($row->net_total_amount) . '</span>')

                ->editColumn('return_discount_amount', fn ($row) => '<span class="return_discount_amount" data-value="' . $row->return_discount_amount . '">' . \App\Utils\Converter::format_in_bdt($row->return_discount_amount) . '</span>')

                ->editColumn('return_tax_amount', fn ($row) => '<span class="return_tax_amount" data-value="' . $row->return_tax_amount . '">' . '(' . $row->return_tax_percent . '%)=' . \App\Utils\Converter::format_in_bdt($row->return_tax_amount) . '</span>')

                ->editColumn('total_return_amount', fn ($row) => '<span class="total_return_amount" data-value="' . $row->total_return_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_return_amount) . '</span>')

                ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . \App\Utils\Converter::format_in_bdt($row->paid) . '</span>')

                ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="' . $row->due . '">' . \App\Utils\Converter::format_in_bdt($row->due) . '</span>')

                ->editColumn('payment_status', function ($row) {

                    $payable = $row->total_return_amount;

                    if ($row->due <= 0) {

                        return '<span class="text-success"><b>' . __("Paid") . '</span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {

                        return '<span class="text-primary"><b>' . __("Partial") . '</b></span>';
                    } elseif ($payable == $row->due) {

                        return '<span class="text-danger"><b>' . __("Due") . '</b></span>';
                    }
                })

                ->rawColumns(['date', 'voucher_no', 'invoice_id', 'total_qty', 'net_total_amount', 'return_discount_amount', 'return_tax_amount', 'total_return_amount', 'paid', 'due', 'branch', 'customer', 'due', 'sale_return_amount', 'payment_status'])
                ->make(true);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.reports.sales_return_report.index', compact('branches', 'customerAccounts'));
    }

    public function print(Request $request)
    {
        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $filteredBranchName = $request->branch_name;
        $filteredCustomerName = $request->customer_name;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $returns = '';

        $query = DB::table('sale_returns')
            ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
            ->leftJoin('accounts as customers', 'sale_returns.customer_account_id', 'customers.id')
            ->leftJoin('branches', 'sale_returns.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

        $this->filter(request: $request, query: $query);

        $returns = $query->select(
            'sale_returns.id',
            'sale_returns.sale_id',
            'sale_returns.branch_id',
            'sale_returns.voucher_no',
            'sale_returns.date',
            'sale_returns.total_qty',
            'sale_returns.net_total_amount',
            'sale_returns.return_discount_type',
            'sale_returns.return_discount_amount',
            'sale_returns.return_tax_percent',
            'sale_returns.return_tax_amount',
            'sale_returns.total_return_amount',
            'sale_returns.paid',
            'sale_returns.due',
            'sales.invoice_id',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
        )->orderBy('sale_returns.date_ts', 'desc')->get();

        return view('sales.reports.sales_return_report.ajax_view.print', compact('returns', 'ownOrParentBranch', 'filteredBranchName', 'filteredCustomerName', 'fromDate', 'toDate'));
    }

    private function filter($request, $query)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sale_returns.branch_id', null);
            } else {

                $query->where('sale_returns.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {

            $query->where('sale_returns.created_by_id', $request->created_by_id);
        }

        if ($request->customer_account_id) {

            if ($request->customer_id == 'NULL') {

                $query->where('sale_returns.customer_account_id', null);
            } else {

                $query->where('sale_returns.customer_account_id', $request->customer_account_id);
            }
        }

        if ($request->payment_status) {

            if ($request->payment_status == PaymentStatus::Paid->value) {

                $query->where('sale_returns.due', '=', 0);
            } else if ($request->payment_status == PaymentStatus::Partial->value) {

                $query->where('sale_returns.paid', '>', 0)->where('sale_returns.due', '>', 0);
            } else if ($request->payment_status == PaymentStatus::Due->value) {

                $query->where('sale_returns.paid', '=', 0);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sale_returns.date_ts', $date_range); // Final
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $query->where('sale_returns.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
