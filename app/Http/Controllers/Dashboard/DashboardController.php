<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Dashboard\DashboardService;
use Yajra\DataTables\Facades\DataTables;

define('TODAY_DATE', Carbon::today());

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService, private BranchService $branchService)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $thisWeek = Carbon::now()->startOfWeek()->format('Y-m-d') . '~' . Carbon::now()->endOfWeek()->format('Y-m-d');
        $thisYear = Carbon::now()->startOfYear()->format('Y-m-d') . '~' . Carbon::now()->endOfYear()->format('Y-m-d');
        $thisMonth = Carbon::now()->startOfMonth()->format('Y-m-d') . '~' . Carbon::now()->endOfMonth()->format('Y-m-d');
        $toDay = Carbon::now()->format('Y-m-d') . '~' . Carbon::now()->endOfDay()->format('Y-m-d');

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ?
            auth()->user()?->branch?->parent_branch_id :
            auth()->user()->branch_id;

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('dashboard.dashboard_1', compact('branches', 'thisWeek', 'thisYear', 'thisMonth', 'toDay'));
    }

    public function cardData(Request $request)
    {
        return $this->dashboardService->dashboardCardData($request);
    }

    public function stockAlert(Request $request)
    {
        if ($request->ajax()) {

            return $this->dashboardService->stockAlertProductsTable($request);
        }
    }

    public function salesOrder(Request $request)
    {
        if ($request->ajax()) {

            return $this->dashboardService->salesOrderTable(request: $request);
        }
    }

    public function salesDueInvoices(Request $request)
    {
        if ($request->ajax()) {

            return $this->dashboardService->salesDueInvoicesTable(request: $request);
        }
    }

    public function purchaseDueInvoices(Request $request)
    {
        if ($request->ajax()) {

            return $this->dashboardService->purchaseDueInvoicesTable(request: $request);
        }
    }

    public function todaySummery(Request $request)
    {
        $branch = '';
        $totalSales = 0;
        $totalSaleDue = 0;
        $totalReceive = 0;
        $totalSaleDiscount = 0;
        $totalSalesReturn = 0;
        $totalSalesShipmentCost = 0;
        $totalPurchase = 0;
        $totalPurchaseDue = 0;
        $totalPayment = 0;
        $totalPurchaseReturn = 0;
        $totalExpense = 0;
        $total_recovered = 0;
        $totalTransferShippingCost = 0;
        $purchaseTotalShipmentCost = 0;
        $totalPayroll = 0;

        $purchases = '';
        $purchasePayment = '';
        $supplierPayment = '';
        $purchaseReturn = '';
        $purchaseTotalShipmentCost = '';
        $sales = '';
        $customerPayment = '';
        $salePayment = '';
        $branchTransfer = '';
        $warehouseTransfer = '';
        $saleReturn = '';
        $expenses = '';
        $adjustments = '';
        $payrolls = '';

        $purchaseQuery = DB::table('purchases')->select(
            DB::raw('sum(total_purchase_amount) as total_purchase'),
            DB::raw('sum(shipment_charge) as total_shipment_charge'),
            DB::raw('sum(due) as total_due')
        );

        $supplierPaymentQ = DB::table('supplier_payments')
            ->where('supplier_payments.type', 1)
            ->select(
                DB::raw('sum(supplier_payments.paid_amount) as t_paid'),
            );

        $purchasePaymentQ = DB::table('purchase_payments')
            ->where('purchase_payments.supplier_payment_id', null)
            ->where('purchase_payments.payment_type', 1)
            ->select(
                DB::raw('sum(paid_amount) as total_paid'),
            );

        $purchaseReturnQuery = DB::table('purchase_returns')->select(
            DB::raw('sum(total_return_amount) as total_return')
        );

        $saleQuery = DB::table('sales')->select(
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(order_discount) as total_discount'),
            DB::raw('sum(shipment_charge) as total_shipment_charge'),
            DB::raw('sum(order_tax_amount) as total_order_tax'),
            DB::raw('sum(due) as total_due'),
        );

        $customerPaymentQ = DB::table('customer_payments')
            ->where('customer_payments.type', 1)
            ->select(
                DB::raw('sum(customer_payments.paid_amount) as t_paid'),
            );

        $salePaymentQ = DB::table('sale_payments')
            ->where('sale_payments.customer_payment_id', null)
            ->where('sale_payments.payment_type', 1)
            ->select(
                DB::raw('sum(paid_amount) as total_paid'),
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
            ->leftJoin('users', 'hrm_payrolls.user_id', 'users.id')
            ->select(DB::raw('sum(hrm_payroll_payments.paid) as total_payroll'));

        if ($request->branch_id) {

            if ($request->branch_id == 'HF') {

                $purchaseQuery->where('purchases.branch_id', null);
                $supplierPaymentQ->where('supplier_payments.branch_id', null);
                $purchasePaymentQ->where('purchase_payments.branch_id', null);
                $customerPaymentQ->where('customer_payments.branch_id', null);
                $salePaymentQ->where('sale_payments.branch_id', null);
                $saleQuery->where('sales.branch_id', null);
                $expenseQuery->where('expanses.branch_id', null);
                $adjustmentQuery->where('stock_adjustments.branch_id', null);
                $purchaseReturnQuery->where('purchase_returns.branch_id', null);
                $saleReturnQuery->where('sale_returns.branch_id', null);
                $branchTransferQuery->where('transfer_stock_to_branches.branch_id', null);
                $warehouseTransferQuery->where('transfer_stock_to_warehouses.branch_id', null);
                $payrollQuery->where('users.branch_id', null);
            } else {

                $purchaseQuery->where('purchases.branch_id', $request->branch_id);
                $supplierPaymentQ->where('supplier_payments.branch_id', $request->branch_id);
                $purchasePaymentQ->where('purchase_payments.branch_id', $request->branch_id);
                $customerPaymentQ->where('customer_payments.branch_id', $request->branch_id);
                $salePaymentQ->where('sale_payments.branch_id', $request->branch_id);
                $saleQuery->where('sales.branch_id', $request->branch_id);
                $expenseQuery->where('expanses.branch_id', $request->branch_id);
                $adjustmentQuery->where('stock_adjustments.branch_id', $request->branch_id);
                $purchaseReturnQuery->where('purchase_returns.branch_id', $request->branch_id);
                $saleReturnQuery->where('sale_returns.branch_id', $request->branch_id);
                $branchTransferQuery->where('transfer_stock_to_branches.branch_id', $request->branch_id);
                $warehouseTransferQuery->where('transfer_stock_to_warehouses.branch_id', $request->branch_id);
                $payrollQuery->where('users.branch_id', $request->branch_id);
                $branch = DB::table('branches')->where('id', $request->branch_id)
                    ->select('name', 'branch_code')
                    ->first();
            }
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $sales = $saleQuery->where('sales.status', 1)->whereDate('report_date', TODAY_DATE)->get();
            $purchases = $purchaseQuery->whereDate('report_date', TODAY_DATE)->get();
            $supplierPayment = $supplierPaymentQ->whereDate('supplier_payments.report_date', TODAY_DATE)->get();
            $purchasePayment = $purchasePaymentQ->whereDate('purchase_payments.report_date', TODAY_DATE)->get();
            $customerPayment = $customerPaymentQ->whereDate('customer_payments.report_date', TODAY_DATE)->get();
            $salePayment = $salePaymentQ->whereDate('sale_payments.report_date', TODAY_DATE)->get();
            $expenses = $expenseQuery->whereDate('report_date', TODAY_DATE)->get();
            $adjustments = $adjustmentQuery->whereDate('report_date_ts', TODAY_DATE)->get();
            $purchaseReturn = $purchaseReturnQuery->whereDate('report_date', TODAY_DATE)->get();
            $saleReturn = $saleReturnQuery->whereDate('report_date', TODAY_DATE)->get();
            $branchTransfer = $branchTransferQuery->whereDate('report_date', TODAY_DATE)->get();
            $warehouseTransfer = $warehouseTransferQuery->whereDate('report_date', TODAY_DATE)->get();
            $payrolls = $payrollQuery->whereDate('hrm_payroll_payments.report_date', TODAY_DATE)->get();
        } else {

            $sales = $saleQuery->where('sales.branch_id', auth()->user()->branch_id)
                ->where('sales.status', 1)->whereDate('report_date', TODAY_DATE)->get();

            $purchases = $purchaseQuery->where('purchases.branch_id', auth()->user()->branch_id)->whereDate('report_date', TODAY_DATE)->get();

            $supplierPayment = $supplierPaymentQ->where('supplier_payments.branch_id', auth()->user()->branch_id)->whereDate('supplier_payments.report_date', TODAY_DATE)->get();

            $purchasePayment = $purchasePaymentQ->where('purchase_payments.branch_id', auth()->user()->branch_id)->whereDate('purchase_payments.report_date', TODAY_DATE)->get();

            $customerPayment = $customerPaymentQ->where('customer_payments.branch_id', auth()->user()->branch_id)->whereDate('customer_payments.report_date', TODAY_DATE)->get();

            $salePayment = $salePaymentQ->where('sale_payments.branch_id', auth()->user()->branch_id)->whereDate('sale_payments.report_date', TODAY_DATE)->get();

            $expenses = $expenseQuery->where('expanses.branch_id', auth()->user()->branch_id)->whereDate('report_date', TODAY_DATE)->get();

            $adjustments = $adjustmentQuery->where('stock_adjustments.branch_id', auth()->user()->branch_id)
                ->whereDate('report_date_ts', TODAY_DATE)->get();

            $purchaseReturn = $purchaseReturnQuery->where('purchase_returns.branch_id', auth()->user()->branch_id)
                ->whereDate('report_date', TODAY_DATE)->get();

            $saleReturn = $saleReturnQuery->where('sale_returns.branch_id', auth()->user()->branch_id)
                ->whereDate('report_date', TODAY_DATE)->get();

            $branchTransfer = $branchTransferQuery->where('transfer_stock_to_branches.branch_id', auth()->user()->branch_id)
                ->whereDate('report_date', TODAY_DATE)->get();

            $warehouseTransfer = $warehouseTransferQuery->where('transfer_stock_to_warehouses.branch_id', auth()->user()->branch_id)
                ->whereDate('report_date', TODAY_DATE)->get();

            $payrolls = $payrollQuery->whereDate('hrm_payroll_payments.report_date', TODAY_DATE)
                ->where('users.branch_id', auth()->user()->branch_id)->get();
        }

        $totalSales = $sales->sum('total_sale');
        $totalSaleDue = $sales->sum('total_due');
        $totalReceive = $customerPayment->sum('t_paid') + $salePayment->sum('total_paid');
        $totalSaleDiscount = $sales->sum('total_discount');
        $totalSaleTax = $sales->sum('total_order_tax');
        $totalSalesReturn = $saleReturn->sum('total_return');
        $totalSalesShipmentCost = $sales->sum('total_shipment_charge');
        $totalPurchase = $purchases->sum('total_purchase');
        $totalPayment = $supplierPayment->sum('t_paid') + $purchasePayment->sum('total_paid');
        $totalPurchaseDue = $purchases->sum('total_due');
        $totalPurchaseReturn = $purchaseReturn->sum('total_return');
        $totalExpense = $expenses->sum('total_expense');
        $total_adjustment = $adjustments->sum('total_adjustment');
        $total_recovered = $adjustments->sum('total_recovered');
        $totalTransferShippingCost = $branchTransfer->sum('total_shipping_cost_br') + $warehouseTransfer->sum('total_shipping_cost_wh');
        $purchaseTotalShipmentCost = $purchases->sum('total_shipment_charge');

        $totalPayroll = $payrolls->sum('total_payroll');
        $branch_id = $request->branch_id;

        $todayProfitParameters = [
            $total_adjustment,
            $total_recovered,
            $totalSales,
            $totalSalesReturn,
            $totalSaleTax,
            $totalExpense,
            $totalPayroll,
            $totalTransferShippingCost,
            $request->branch_id,
        ];

        $todayProfit = $this->todayProfit(...$todayProfitParameters);

        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);

        return view('dashboard.ajax_view.today_summery', compact(
            'totalSales',
            'totalSaleDue',
            'totalReceive',
            'totalSaleDiscount',
            'totalSalesReturn',
            'totalSalesShipmentCost',
            'totalPurchase',
            'totalPurchaseDue',
            'totalPayment',
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
            'todayProfit'
        ));
    }

    public function todayProfit($totalAdjust, $totalRecovered, $totalSale, $totalSalesReturn, $totalOrderTax, $totalExpanse, $totalPayroll, $totalTransferCost, $branch_id)
    {
        // $saleProductQuery = DB::table('sale_products')->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
        //     ->select(DB::raw('sum(quantity * unit_cost_inc_tax) as total_unit_cost'));

        $saleProductQuery = DB::table('purchase_sale_product_chains')
            ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
            ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->select(
                DB::raw('SUM(net_unit_cost * sold_qty) as total_unit_cost')
            );

        if ($branch_id) {
            if ($branch_id == 'HF') {
                $saleProductQuery->where('sales.branch_id', null);
            } else {
                $saleProductQuery->where('sales.branch_id', $branch_id);
            }
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $saleProducts = $saleProductQuery->where('sales.status', 1)
                ->whereDate('sales.report_date', TODAY_DATE)->get();
        } else {
            $saleProducts = $saleProductQuery->where('sales.status', 1)
                ->whereDate('sales.report_date', TODAY_DATE)->where('sales.branch_id', auth()->user()->branch_id)->get();
        }

        $totalTotalUnitCost = $saleProducts->sum('total_unit_cost');

        return $netProfit = ($totalSale + $totalRecovered)
            - $totalAdjust
            - $totalExpanse
            - $totalSalesReturn
            - $totalOrderTax
            - $totalPayroll
            - $totalTotalUnitCost
            - $totalTransferCost;
    }

    public function changeLang($lang)
    {
        session(['lang' => $lang]);

        return redirect()->back();
    }
}
