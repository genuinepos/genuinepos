<?php

namespace App\Http\Controllers\report;

// use App\Models\Purchase;
use App\Models\Sale;
// use App\Models\ProductOpeningStock;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProfitLossReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of profit loss report
    public function index()
    {
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.profit_loss_report.index', compact('branches'));
    }

    // Sale purchase and profit
    public function salePurchaseProfit()
    {
        //return  $request->date_range;
        $stock_adjustments = '';
        $sales = '';
        $saleProducts = '';
        $expanses = '';
        $payrolls = '';
        $saleProducts = '';
        $transferStBranch = '';
        $transferStWarehouse = '';

        $transferStBranchQuery = DB::table('transfer_stock_to_branches')
        ->select(DB::raw('sum(shipping_charge) as b_total_shipment_charge'));

        $transferStWarehouseQuery = DB::table('transfer_stock_to_warehouses')
        ->select(DB::raw('sum(shipping_charge) as w_total_shipment_charge'));

        $saleProductQuery = DB::table('sale_products')->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
        ->select(DB::raw('sum(unit_cost_inc_tax) as total_unit_cost'));

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
            DB::raw('sum(recovered_amount) as total_recovered')
        );
        
        $saleQuery = DB::table('sales')->select( 
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(sale_return_amount) as total_return'),
            DB::raw('sum(order_tax_amount) as total_order_tax'),
        );

        $payrollQuery = DB::table('hrm_payroll_payments')
        ->leftJoin('hrm_payrolls', 'hrm_payroll_payments.payroll_id', 'hrm_payrolls.id')
        ->leftJoin('admin_and_users', 'hrm_payrolls.user_id', 'admin_and_users.id')
        ->select(DB::raw('sum(hrm_payroll_payments.paid) as total_payroll'));

        $expenseQuery = DB::table('expanses')->select(DB::raw('sum(net_total_amount) as total_expense'));

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $stock_adjustments = $adjustmentQuery->groupBy('stock_adjustments.id')->get();
            $sales = $saleQuery->groupBy('sales.id')->get();
            $expense = $expenseQuery->groupBy('expanses.id')->get();
            $payrolls = $payrollQuery->groupBy('hrm_payroll_payments.id')->get();
            $saleProducts = $saleProductQuery->groupBy('sale_products.id')->get();
            $transferStBranch = $transferStBranchQuery->groupBy('transfer_stock_to_branches.id')->get();
            $transferStWarehouse = $transferStWarehouseQuery->groupBy('transfer_stock_to_warehouses.id')->get();
        } else {
            $stock_adjustments = $adjustmentQuery->groupBy('stock_adjustments.id')->where('branch_id', auth()->user()->branch_id)->get();
            $sales = $saleQuery->groupBy('sales.id')->where('branch_id', auth()->user()->branch_id)->get();
            $expense = $expenseQuery->groupBy('expanses.id')->where('branch_id', auth()->user()->branch_id)->get();
            $payrolls = $payrollQuery->groupBy('hrm_payroll_payments.id')
            ->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
            $saleProducts = $saleProductQuery->groupBy('sale_products.id')->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
            $transferStBranch = $transferStBranchQuery->groupBy('transfer_stock_to_branches.id')->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
            $transferStWarehouse = $transferStWarehouseQuery->groupBy('transfer_stock_to_warehouses.id')->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
        }

        $totalStockAdjustmentAmount =  $stock_adjustments->sum('total_adjustment');
        $totalStockAdjustmentRecovered =  $stock_adjustments->sum('total_recovered');
        $totalSale = $sales->sum('total_sale');
        $totalReturn = $sales->sum('total_return');
        $totalOrderTax = $sales->sum('total_order_tax');
        $totalExpense = $expense->sum('total_expense');
        $totalPayroll = $payrolls->sum('total_payroll');
        $totalTotalUnitCost = $saleProducts->sum('total_unit_cost');
        $totalTransferShipmentCost = $transferStBranch->sum('b_total_shipment_charge') + $transferStWarehouse->sum('w_total_shipment_charge');
        
        return view(
            'reports.profit_loss_report.ajax_view.sale_purchase_and_profit_view',
            compact(
                'totalStockAdjustmentAmount',
                'totalStockAdjustmentRecovered',
                'totalSale',
                'totalExpense',
                'totalReturn',
                'totalOrderTax',
                'totalPayroll',
                'totalTotalUnitCost',
                'totalTransferShipmentCost',
            )
        );
    }

    // Filter sale purchase and profit
    public function filterSalePurchaseProfit(Request $request)
    {
        $stock_adjustments = '';
        $sales = '';
        $saleProducts = '';
        $expanses = '';
        $payrolls = '';
        $saleProducts = '';
        $transferStBranch = '';
        $transferStWarehouse = '';

        $transferStBranchQuery = DB::table('transfer_stock_to_branches')
        ->select(DB::raw('sum(shipping_charge) as b_total_shipment_charge'));

        $transferStWarehouseQuery = DB::table('transfer_stock_to_warehouses')
        ->select(DB::raw('sum(shipping_charge) as w_total_shipment_charge'));

        $saleProductQuery = DB::table('sale_products')->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
        ->select(DB::raw('sum(unit_cost_inc_tax) as total_unit_cost'));

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
            DB::raw('sum(recovered_amount) as total_recovered')
        );
        
        $saleQuery = DB::table('sales')->select( 
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(sale_return_amount) as total_return'),
            DB::raw('sum(order_tax_amount) as total_order_tax'),
        );

        $expenseQuery = DB::table('expanses')->select(DB::raw('sum(net_total_amount) as total_expense'));

        $payrollQuery = DB::table('hrm_payroll_payments')
        ->leftJoin('hrm_payrolls', 'hrm_payroll_payments.payroll_id', 'hrm_payrolls.id')
        ->leftJoin('admin_and_users', 'hrm_payrolls.user_id', 'admin_and_users.id')
        ->select(DB::raw('sum(hrm_payroll_payments.paid) as total_payroll'));

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $adjustmentQuery->where('branch_id', NULL);
                $saleQuery->where('sales.branch_id', NULL);
                $expenseQuery->where('expanses.branch_id', NULL);
                $payrollQuery->where('admin_and_users.branch_id', NULL);
                $saleProductQuery->where('sales.branch_id', NULL);
                $transferStBranchQuery->where('transfer_stock_to_branches.branch_id', NULL);
                $transferStWarehouseQuery->where('transfer_stock_to_warehouses.branch_id', NULL);
            } else {
                $adjustmentQuery->where('branch_id', $request->branch_id);
                $expenseQuery->where('expanses.branch_id', $request->branch_id);
                $saleQuery->where('sales.branch_id', $request->branch_id);
                $payrollQuery->where('admin_and_users.branch_id', $request->branch_id);
                $saleProductQuery->where('sales.branch_id', $request->branch_id);
                $transferStBranchQuery->where('transfer_stock_to_branches.branch_id', $request->branch_id);
                $transferStWarehouseQuery->where('transfer_stock_to_warehouses.branch_id', $request->branch_id);
            }
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $adjustmentQuery->whereBetween('stock_adjustments.report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $saleQuery->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $expenseQuery->whereBetween('expanses.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $payrollQuery->whereBetween('hrm_payroll_payments.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $saleProductQuery->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $transferStBranchQuery->whereBetween('transfer_stock_to_branches.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $transferStWarehouseQuery->whereBetween('transfer_stock_to_warehouses.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $stock_adjustments = $adjustmentQuery->groupBy('stock_adjustments.id')->get();
            $sales = $saleQuery->groupBy('sales.id')->get();
            $expense = $expenseQuery->groupBy('expanses.id')->get();
            $payrolls = $payrollQuery->groupBy('hrm_payroll_payments.id')->get();
            $saleProducts = $saleProductQuery->groupBy('sale_products.id')->get();
            $transferStBranch = $transferStBranchQuery->groupBy('transfer_stock_to_branches.id')->get();
            $transferStWarehouse = $transferStWarehouseQuery->groupBy('transfer_stock_to_warehouses.id')->get();
        } else {
            $stock_adjustments = $adjustmentQuery->groupBy('stock_adjustments.id')
                ->where('branch_id', auth()->user()->branch_id)->get();
            $sales = $saleQuery->groupBy('sales.id')->where('branch_id', auth()->user()->branch_id)->get();
            $expense = $expenseQuery->groupBy('expanses.id')->where('branch_id', auth()->user()->branch_id)->get();
            $payrolls = $payrollQuery->groupBy('hrm_payroll_payments.id')
            ->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
            $saleProducts = $saleProductQuery->groupBy('sale_products.id')->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
            $transferStBranch = $transferStBranchQuery->groupBy('transfer_stock_to_branches.id')->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
            $transferStWarehouse = $transferStWarehouseQuery->groupBy('transfer_stock_to_warehouses.id')->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
        }

        $totalStockAdjustmentAmount =  $stock_adjustments->sum('total_adjustment');
        $totalStockAdjustmentRecovered =  $stock_adjustments->sum('total_recovered');
        $totalSale = $sales->sum('total_sale');
        $totalReturn = $sales->sum('total_return');
        $totalOrderTax = $sales->sum('total_order_tax');
        $totalExpense = $expense->sum('total_expense');
        $totalPayroll = $payrolls->sum('total_payroll');
        $totalTotalUnitCost = $saleProducts->sum('total_unit_cost');
        $totalTransferShipmentCost = $transferStBranch->sum('b_total_shipment_charge') + $transferStWarehouse->sum('w_total_shipment_charge');

        return view(
            'reports.profit_loss_report.ajax_view.filtered_sale_purchase_and_profit_view',
            compact(
                'totalStockAdjustmentAmount',
                'totalStockAdjustmentRecovered',
                'totalSale',
                'totalExpense',
                'totalReturn',
                'totalOrderTax',
                'totalPayroll',
                'totalTotalUnitCost',
                'totalTransferShipmentCost',
            )
        );
    }

    // By profit method. Ex: product wise, category wise, branch wise, invoice wise etc.
    public function profitBy(Request $request)
    {
        //return 'pb-'.$profit_by.', bpr- '.$by_profit_range;
        //return $request->by_profit_range;
        $by_profit_range = $request->by_profit_range;
        $form_date = '';
        $to_date = '';
        if ($by_profit_range != 'current_year') {
            $by_profit_range = explode('-', trim($request->by_profit_range));
            $form_date = date('Y-m-d', strtotime($by_profit_range[0] . ' -1 days'));
            $to_date = date('Y-m-d', strtotime($by_profit_range[1] . ' +1 days'));
            //return $form_date . ' - ' . $to_date;
        }

        if ($request->profit_by == 'by_product') {
            $products = DB::table('products')->where('number_of_sale', '>', 0)->get();
            return view(
                'reports.profit_loss_report.ajax_view.profit_by_product',
                compact('products', 'by_profit_range', 'form_date', 'to_date')
            );
        } elseif ($request->profit_by == 'by_category') {

            $categories = Category::with('products')->get();
            return view('reports.profit_loss_report.ajax_view.profit_by_category', compact('categories', 'by_profit_range', 'form_date', 'to_date'));
        } elseif ($request->profit_by == 'by_brand') {

            $brands = Brand::with('products')->get();
            return view('reports.profit_loss_report.ajax_view.profit_by_brand', compact('brands', 'by_profit_range', 'form_date', 'to_date'));
        } elseif ($request->profit_by == 'by_branch') {

            $branches = Branch::all();
            return view('reports.profit_loss_report.ajax_view.profit_by_branch', compact('branches', 'by_profit_range', 'form_date', 'to_date'));
        } elseif ($request->profit_by == 'by_invoice') {

            $by_profit_range = $request->by_profit_range;
            if ($by_profit_range != 'current_year') {
                $by_profit_range = explode('-', trim($request->by_profit_range));
                $form_date = date('Y-m-d', strtotime($by_profit_range[0] . ' -1 days'));
                $to_date = date('Y-m-d', strtotime($by_profit_range[1] . ' +1 days'));
                $invoices = Sale::with(['sale_products'])->where('status', 1)
                    ->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00'])->get();
                return view('reports.profit_loss_report.ajax_view.profit_by_invoice', compact('invoices'));
            } else {
                $invoices = Sale::with(['sale_products'])->where('status', 1)
                    ->whereYear('report_date', date('Y'))->get();
                return view('reports.profit_loss_report.ajax_view.profit_by_invoice', compact('invoices'));
            }
        }
    }

    // Print Profit Loss method
    public function printProfitLoss(Request $request)
    {
        $stock_adjustments = '';
        $sales = '';
        $saleProducts = '';
        $expanses = '';
        $payrolls = '';
        $saleProducts = '';
        $transferStBranch = '';
        $transferStWarehouse = '';
        $fromDate = '';
        $toDate = '';
        $branch_id = $request->branch_id;

        $transferStBranchQuery = DB::table('transfer_stock_to_branches')
        ->select(DB::raw('sum(shipping_charge) as b_total_shipment_charge'));

        $transferStWarehouseQuery = DB::table('transfer_stock_to_warehouses')
        ->select(DB::raw('sum(shipping_charge) as w_total_shipment_charge'));

        $saleProductQuery = DB::table('sale_products')->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
        ->select(DB::raw('sum(unit_cost_inc_tax) as total_unit_cost'));

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
            DB::raw('sum(recovered_amount) as total_recovered')
        );
        
        $saleQuery = DB::table('sales')->select( 
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(sale_return_amount) as total_return'),
            DB::raw('sum(order_tax_amount) as total_order_tax'),
        );

        $expenseQuery = DB::table('expanses')->select(DB::raw('sum(net_total_amount) as total_expense'));

        $payrollQuery = DB::table('hrm_payroll_payments')
        ->leftJoin('hrm_payrolls', 'hrm_payroll_payments.payroll_id', 'hrm_payrolls.id')
        ->leftJoin('admin_and_users', 'hrm_payrolls.user_id', 'admin_and_users.id')
        ->select(DB::raw('sum(hrm_payroll_payments.paid) as total_payroll'));

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $adjustmentQuery->where('branch_id', NULL);
                $saleQuery->where('sales.branch_id', NULL);
                $expenseQuery->where('expanses.branch_id', NULL);
                $payrollQuery->where('admin_and_users.branch_id', NULL);
                $saleProductQuery->where('sales.branch_id', NULL);
                $transferStBranchQuery->where('transfer_stock_to_branches.branch_id', NULL);
                $transferStWarehouseQuery->where('transfer_stock_to_warehouses.branch_id', NULL);
            } else {
                $adjustmentQuery->where('branch_id', $request->branch_id);
                $expenseQuery->where('expanses.branch_id', $request->branch_id);
                $saleQuery->where('sales.branch_id', $request->branch_id);
                $payrollQuery->where('admin_and_users.branch_id', $request->branch_id);
                $saleProductQuery->where('sales.branch_id', $request->branch_id);
                $transferStBranchQuery->where('transfer_stock_to_branches.branch_id', $request->branch_id);
                $transferStWarehouseQuery->where('transfer_stock_to_warehouses.branch_id', $request->branch_id);
            }
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $fromDate = date('Y-m-d', strtotime($date_range[0]));
            $toDate = date('Y-m-d', strtotime($date_range[1]));
            $adjustmentQuery->whereBetween('stock_adjustments.report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $saleQuery->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $expenseQuery->whereBetween('expanses.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $payrollQuery->whereBetween('hrm_payroll_payments.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $saleProductQuery->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $transferStBranchQuery->whereBetween('transfer_stock_to_branches.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $transferStWarehouseQuery->whereBetween('transfer_stock_to_warehouses.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $stock_adjustments = $adjustmentQuery->groupBy('stock_adjustments.id')->get();
            $sales = $saleQuery->groupBy('sales.id')->get();
            $expense = $expenseQuery->groupBy('expanses.id')->get();
            $payrolls = $payrollQuery->groupBy('hrm_payroll_payments.id')->get();
            $saleProducts = $saleProductQuery->groupBy('sale_products.id')->get();
            $transferStBranch = $transferStBranchQuery->groupBy('transfer_stock_to_branches.id')->get();
            $transferStWarehouse = $transferStWarehouseQuery->groupBy('transfer_stock_to_warehouses.id')->get();
        } else {
            $stock_adjustments = $adjustmentQuery->groupBy('stock_adjustments.id')
                ->where('branch_id', auth()->user()->branch_id)->get();
            $sales = $saleQuery->groupBy('sales.id')->where('branch_id', auth()->user()->branch_id)->get();
            $expense = $expenseQuery->groupBy('expanses.id')->where('branch_id', auth()->user()->branch_id)->get();
            $payrolls = $payrollQuery->groupBy('hrm_payroll_payments.id')
            ->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
            $saleProducts = $saleProductQuery->groupBy('sale_products.id')->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
            $transferStBranch = $transferStBranchQuery->groupBy('transfer_stock_to_branches.id')->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
            $transferStWarehouse = $transferStWarehouseQuery->groupBy('transfer_stock_to_warehouses.id')->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
        }

        $totalStockAdjustmentAmount =  $stock_adjustments->sum('total_adjustment');
        $totalStockAdjustmentRecovered =  $stock_adjustments->sum('total_recovered');
        $totalSale = $sales->sum('total_sale');
        $totalReturn = $sales->sum('total_return');
        $totalOrderTax = $sales->sum('total_order_tax');
        $totalExpense = $expense->sum('total_expense');
        $totalPayroll = $payrolls->sum('total_payroll');
        $totalTotalUnitCost = $saleProducts->sum('total_unit_cost');
        $totalTransferShipmentCost = $transferStBranch->sum('b_total_shipment_charge') + $transferStWarehouse->sum('w_total_shipment_charge');

        return view(
            'reports.profit_loss_report.ajax_view.printProfitLoss',
            compact(
                'totalStockAdjustmentAmount',
                'totalStockAdjustmentRecovered',
                'totalSale',
                'totalExpense',
                'totalReturn',
                'totalOrderTax',
                'totalPayroll',
                'totalTotalUnitCost',
                'totalTransferShipmentCost',
                'fromDate',
                'toDate',
                'branch_id',
            )
        );
       
    }
}
