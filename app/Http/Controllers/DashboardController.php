<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Editor\Fields\DateTime;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Admin dashboard
    public function index()
    {
        $thisWeek = Carbon::now()->startOfWeek() . '~' . Carbon::now()->endOfWeek();
        $thisYear = Carbon::now()->startOfYear() . '~' . Carbon::now()->endOfYear();
        $thisMonth = Carbon::now()->startOfMonth() . '~' . Carbon::now()->endOfMonth();
        $toDay = Carbon::now() . '~' . Carbon::now();
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('dashboard.dashboard_1', compact('branches', 'thisWeek', 'thisYear', 'thisMonth', 'toDay'));
    }

    // Get dashboard card data
    public function cardData(Request $request)
    {
        $totalSales = 0;
        $totalSaleDue = 0;
        $totalSaleDiscount = 0;
        $totalPurchase = 0;
        $totalPurchaseDue = 0;
        $totalExpense = 0;

        $purchases = '';
        $sales = '';
        $expenses = '';
        $products = '';
        $users = '';
        $adjustments = '';

        $userQuery = DB::table('admin_and_users');
        $productQuery = DB::table('product_branches');
        $purchaseQuery = DB::table('purchases')
        ->select(
            DB::raw('sum(total_purchase_amount) as total_purchase'),
            DB::raw('sum(case when due > 0 then due end) as total_due'),
        );

        $saleQuery = DB::table('sales')->select(
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(case when due > 0 then due end) as total_due'),
            DB::raw('sum(order_discount) as total_discount')
        );

        $expenseQuery = DB::table('expanses')->select(
            DB::raw('sum(net_total_amount) as total_expense'),
        );

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
        );

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $purchaseQuery->where('purchases.branch_id', NULL);
                $saleQuery->where('sales.branch_id', NULL);
                $expenseQuery->where('expanses.branch_id', NULL);
                $userQuery->where('admin_and_users.branch_id', NULL);
                $adjustmentQuery->where('stock_adjustments.branch_id', NULL);
            } else {
                $purchaseQuery->where('purchases.branch_id', $request->branch_id);
                $saleQuery->where('sales.branch_id', $request->branch_id);
                $expenseQuery->where('expanses.branch_id', $request->branch_id);
                $userQuery->where('admin_and_users.branch_id', $request->branch_id);
                $adjustmentQuery->where('stock_adjustments.branch_id', $request->branch_id);
            }
        }

        if ($request->date_range) {
            $date_range = explode('~', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $saleQuery->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
            $purchaseQuery->whereBetween('purchases.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $expenseQuery->whereBetween('expanses.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $adjustmentQuery->whereBetween('stock_adjustments.report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $sales = $saleQuery->groupBy('sales.id')->get();
            $purchases = $purchaseQuery->groupBy('purchases.id')->get();
            $expenses = $expenseQuery->groupBy('expanses.id')->get();
            $users = $userQuery->count();
            $adjustments = $adjustmentQuery->groupBy('stock_adjustments.id')->get();
        } else {
            $sales = $saleQuery->where('sales.branch_id', auth()->user()->branch_id)->groupBy('sales.id')->get();
            $purchases = $purchaseQuery->where('purchases.branch_id', auth()->user()->branch_id)->groupBy('purchases.id')->get();
            $expenses = $expenseQuery->where('expanses.branch_id', auth()->user()->branch_id)->groupBy('expanses.id')->get();
            $users = $userQuery->where('admin_and_users.branch_id', auth()->user()->branch_id)->count();
            $adjustments = $adjustmentQuery->where('stock_adjustments.branch_id', auth()->user()->branch_id)
            ->groupBy('stock_adjustments.id')->get();
        }


        $totalSales = $sales->sum('total_sale');
        $totalSaleDue = $sales->sum('total_due');
        $totalSaleDiscount = $sales->sum('total_discount');

        $totalPurchase = $purchases->sum('total_purchase');
        $totalPurchaseDue = $purchases->sum('total_due');

        $totalExpense = $expenses->sum('total_expense');

        $products = $productQuery->count();
        $total_adjustment = $adjustments->sum('total_adjustment');
      
        return response()->json([
            'total_sale' => $totalSales,
            'totalSaleDue' => $totalSaleDue,
            'totalSaleDiscount' => $totalSaleDiscount,
            'totalPurchase' => $totalPurchase,
            'totalPurchaseDue' => $totalPurchaseDue,
            'totalExpense' => $totalExpense,
            'users' => $users,
            'products' => $products,
            'total_adjustment' => $total_adjustment,
        ]);
    }

    public function stockAlert(Request $request)
    {
        if ($request->ajax()) {
            $products = DB::table('products')->where('quantity', '<=', 'alert_quantity')
                ->join('units', 'products.unit_id', 'units.id')
                ->select(
                    [
                        'products.name',
                        'products.product_code',
                        'products.alert_quantity',
                        'products.quantity',
                        'units.name as unit_name',
                    ]
                )->get();

            return DataTables::of($products)
                ->addIndexColumn()
                ->editColumn('stock', function ($row) {
                    $quantity = '';
                    if ($row->quantity <= 0) {
                        $quantity = '<span class="text-danger"><b>' . $row->quantity . '</b></span>';
                    } else {
                        $quantity = '<b>' . $row->quantity . '</b>';
                    }

                    return $quantity . ' (' . $row->unit_name . ')';
                })->rawColumns(['stock'])->make(true);
        }
    }

    public function saleOrder(Request $request)
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

            if ($request->date_range) {
                $date_range = explode('~', $request->date_range);
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
                    'admin_and_users.prefix as c_prefix',
                    'admin_and_users.name as c_name',
                    'admin_and_users.last_name as c_last_name',
                )->orderBy('id', 'desc')->where('sales.shipment_status', 1)->get();
            } else {
                $sales = $query->select(
                    'sales.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'customers.name as customer_name',
                    'admin_and_users.prefix as c_prefix',
                    'admin_and_users.name as c_name',
                    'admin_and_users.last_name as c_last_name',
                )->where('sales.branch_id', auth()->user()->branch_id)->where('sales.shipment_status', 1)->get();
            }

            return DataTables::of($sales)
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name']  . '(<b>HO</b>)';
                    }
                })
                ->editColumn('shipment_status',  function ($row) {
                    if ($row->shipment_status == 1) {
                        return '<span class="badge bg-warning">Ordered</span>';
                    }
                })
                ->editColumn('customer',  function ($row) {
                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('created_by',  function ($row) {
                    return $row->c_prefix . ' ' . $row->c_name . ' ' . $row->c_last_name;
                })
                ->rawColumns(['date', 'from', 'customer', 'created_by', 'shipment_status'])
                ->make(true);
        }
    }

    public function saleDue(Request $request)
    {
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

            if ($request->date_range) {
                $date_range = explode('~', $request->date_range);
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
                )->where('sales.due', '>', 0)->orderBy('id', 'desc')->get();
            } else {
                $sales = $query->select(
                    'sales.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'customers.name as customer_name',
                )->where('sales.branch_id', auth()->user()->branch_id)->where('sales.due', '>', 0)->get();
            }

            return DataTables::of($sales)
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name']  . '(<b>HO</b>)';
                    }
                })
                ->editColumn('customer',  function ($row) {
                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('due',  function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->due;
                })
                ->rawColumns(['date', 'from', 'customer', 'due'])
                ->make(true);
        }
    }

    public function purchaseDue(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $purchases = '';
            $query = DB::table('purchases')
                ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
                ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('purchases.branch_id', NULL);
                } else {
                    $query->where('purchases.branch_id', $request->branch_id);
                }
            }

            if ($request->date_range) {
                $date_range = explode('~', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
                $query->whereBetween('purchases.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $purchases = $query->select(
                    'purchases.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'suppliers.name as sup_name',
                )->where('purchases.due', '!=', 0)->orderBy('id', 'desc')->get();
            } else {
                $purchases = $query->select(
                    'purchases.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'suppliers.name as sup_name',
                )->where('purchases.branch_id', auth()->user()->branch_id)->where('purchases.due', '!=', 0)->get();
            }

            return DataTables::of($purchases)
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name']  . '(<b>HO</b>)';
                    }
                })
                ->editColumn('due',  function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->due;
                })
                ->rawColumns(['date', 'from', 'due'])
                ->make(true);
        }
    }

    public function todaySummery(Request $request)
    {
        $branch = '';
        $totalSales = 0;
        $totalSaleDiscount = 0;
        $totalSalesReturn = 0;
        $totalSalesShipmentCost = 0;
        $totalPurchase = 0;
        $totalPurchaseReturn = 0;
        $totalExpense = 0;
        $total_recovered = 0;
        $totalTransferShippingCost = 0;
        $purchaseTotalShipmentCost = 0;
        $totalPayroll = 0;

        $purchases = '';
        $purchaseReturn = '';
        $purchaseTotalShipmentCost = '';
        $sales = '';
        $branchTransfer = '';
        $warehouseTransfer = '';
        $saleReturn = '';
        $expenses = '';
        $adjustments = '';
        $payrolls = '';

        $purchaseQuery = DB::table('purchases')->select(
            DB::raw('sum(total_purchase_amount) as total_purchase'),
            DB::raw('sum(shipment_charge) as total_shipment_charge')
        );

        $purchaseReturnQuery = DB::table('purchase_returns')->select(
            DB::raw('sum(total_return_amount) as total_return')
        );

        $saleQuery = DB::table('sales')->select(
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(order_discount) as total_discount'),
            DB::raw('sum(shipment_charge) as total_shipment_charge')
        );

        $saleReturnQuery = DB::table('sale_returns')
        ->select(DB::raw('sum(total_return_amount) as total_return'));

        $expenseQuery = DB::table('expanses')->select(DB::raw('sum(net_total_amount) as total_expense'));

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
            DB::raw('sum(recovered_amount) as total_recovered')
        );

        $branchTransferQuery = DB::table('transfer_stock_to_branches')->select(
            DB::raw('sum(shipping_charge) as total_shipping_cost_br')
        );

        $warehouseTransferQuery = DB::table('transfer_stock_to_warehouses')->select(
            DB::raw('sum(shipping_charge) as total_shipping_cost_wh')
        );

        $payrollQuery = DB::table('hrm_payroll_payments')
        ->leftJoin('hrm_payrolls', 'hrm_payroll_payments.payroll_id', 'hrm_payrolls.id')
        ->leftJoin('admin_and_users', 'hrm_payrolls.user_id', 'admin_and_users.id')
        ->select(DB::raw('sum(hrm_payroll_payments.paid) as total_payroll'));

        if ($request->branch_id) {
            if ($request->branch_id == 'HF') {
                $purchaseQuery->where('purchases.branch_id', NULL);
                $saleQuery->where('sales.branch_id', NULL);
                $expenseQuery->where('expanses.branch_id', NULL);
                $adjustmentQuery->where('stock_adjustments.branch_id', NULL);
                $purchaseReturnQuery->where('purchase_returns.branch_id', NULL);
                $saleReturnQuery->where('sale_returns.branch_id', NULL);
                $branchTransferQuery->where('transfer_stock_to_branches.branch_id', NULL);
                $warehouseTransferQuery->where('transfer_stock_to_warehouses.branch_id', NULL);
                $payrollQuery->where('admin_and_users.branch_id', NULL);
            } else {
                $purchaseQuery->where('purchases.branch_id', $request->branch_id);
                $saleQuery->where('sales.branch_id', $request->branch_id);
                $expenseQuery->where('expanses.branch_id', $request->branch_id);
                $adjustmentQuery->where('stock_adjustments.branch_id', $request->branch_id);
                $purchaseReturnQuery->where('purchase_returns.branch_id', $request->branch_id);
                $saleReturnQuery->where('sale_returns.branch_id', $request->branch_id);
                $branchTransferQuery->where('transfer_stock_to_branches.branch_id', $request->branch_id);
                $warehouseTransferQuery->where('transfer_stock_to_warehouses.branch_id', $request->branch_id);
                $payrollQuery->where('admin_and_users.branch_id', $request->branch_id);
                $branch = DB::table('branches')->where('id', $request->branch_id)
                ->select('name', 'branch_code')
                ->first();
            }
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $sales = $saleQuery->groupBy('sales.id')->whereDate('created_at', Carbon::today())->get();
            $purchases = $purchaseQuery->groupBy('purchases.id')->whereDate('created_at', Carbon::today())->get();
            $expenses = $expenseQuery->groupBy('expanses.id')->whereDate('created_at', Carbon::today())->get();
            $adjustments = $adjustmentQuery->groupBy('stock_adjustments.id')->whereDate('created_at', Carbon::today())->get();
            $purchaseReturn = $purchaseReturnQuery->groupBy('purchase_returns.id')->whereDate('created_at', Carbon::today())->get();
            $saleReturn = $saleReturnQuery->groupBy('sale_returns.id')->whereDate('created_at', Carbon::today())->get();
            $branchTransfer = $branchTransferQuery->groupBy('transfer_stock_to_branches.id')->whereDate('report_date', Carbon::today())->get();
            $warehouseTransfer = $warehouseTransferQuery->groupBy('transfer_stock_to_warehouses.id')->whereDate('report_date', Carbon::today())->get();
            $payrolls = $payrollQuery->groupBy('hrm_payroll_payments.id')
            ->whereDate('hrm_payroll_payments.created_at', Carbon::today())->get();
        } else {
            $sales = $saleQuery->where('sales.branch_id', auth()->user()->branch_id)
            ->groupBy('sales.id')->whereDate('created_at', Carbon::today())->get();
            
            $purchases = $purchaseQuery->where('purchases.branch_id', auth()->user()->branch_id)
            ->groupBy('purchases.id')->whereDate('created_at', Carbon::today())->get();

            $expenses = $expenseQuery->where('expanses.branch_id', auth()->user()->branch_id)
            ->groupBy('expanses.id')
            ->whereDate('created_at', Carbon::today())->get();

            $adjustments = $adjustmentQuery->where('stock_adjustments.branch_id', auth()->user()->branch_id)
            ->groupBy('stock_adjustments.id')
            ->whereDate('created_at', Carbon::today())->get();

            $purchaseReturn = $purchaseReturnQuery->groupBy('purchase_returns.id')
            ->where('purchase_returns.branch_id', auth()->user()->branch_id)
            ->whereDate('created_at', Carbon::today())->get();

            $saleReturn = $saleReturnQuery->groupBy('sale_returns.id')
            ->where('sale_returns.branch_id', auth()->user()->branch_id)
            ->whereDate('created_at', Carbon::today())->get();

            $branchTransfer = $branchTransferQuery->groupBy('transfer_stock_to_branches.id')
            ->where('transfer_stock_to_branches.branch_id', auth()->user()->branch_id)
            ->whereDate('report_date', Carbon::today())->get();

            $warehouseTransfer = $warehouseTransferQuery->groupBy('transfer_stock_to_warehouses.id')
            ->where('transfer_stock_to_warehouses.branch_id', auth()->user()->branch_id)
            ->whereDate('report_date', Carbon::today())->get();

            $payrolls = $payrollQuery->groupBy('hrm_payroll_payments.id')
            ->whereDate('hrm_payroll_payments.created_at', Carbon::today())
            ->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
        }

        $totalSales = $sales->sum('total_sale');
        $totalSaleDiscount = $sales->sum('total_discount');
        $totalSalesReturn = $saleReturn->sum('total_return');
        $totalSalesShipmentCost = $sales->sum('total_shipment_charge');
        $totalPurchase = $purchases->sum('total_purchase');
        $totalPurchaseReturn = $purchaseReturn->sum('total_return');
        $totalExpense = $expenses->sum('total_expense');
        $total_adjustment = $adjustments->sum('total_adjustment');
        $total_recovered = $adjustments->sum('total_recovered');
        $totalTransferShippingCost = $branchTransfer->sum('total_shipping_cost_br') + $warehouseTransfer->sum('total_shipping_cost_wh');
        $purchaseTotalShipmentCost = $purchases->sum('total_shipment_charge');
       
        $totalPayroll = $payrolls->sum('total_payroll');
        $branch_id = $request->branch_id;
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('dashboard.ajax_view.today_summery', compact(
            'totalSales', 
            'totalSaleDiscount',
            'totalSalesReturn', 
            'totalSalesShipmentCost',
            'totalPurchase',
            'totalPurchaseReturn',
            'totalExpense',
            'total_adjustment',
            'total_recovered',
            'totalTransferShippingCost',
            'purchaseTotalShipmentCost',
            'totalPayroll',
            'branches',
            'branch',
            'branch_id',
        ));
    }

    public function changeLang($lang)
    {
        session(['lang' => $lang]);
        return redirect()->back();
    }
}
