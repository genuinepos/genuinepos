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
        $opening_stocks = DB::table('product_opening_stocks')->whereYear('created_at', date('Y'))
            ->select('id', 'unit_cost_inc_tax', 'subtotal')
            ->get();
        $stock_adjustments = DB::table('stock_adjustments')->whereYear('report_date_ts', date('Y'))->get();
        $purchases = DB::table('purchases')->whereYear('report_date', date('Y'))->get();
        $sales = DB::table('sales')->whereYear('report_date', date('Y'))->get();
        $products = DB::table('products')->join('taxes', 'products.tax_id', 'taxes.id')
            ->select(['products.*', 'taxes.tax_percent'])
            ->get();
        $expanses = DB::table('expanses')->whereYear('report_date', date('Y'))->get();
        $transfer_to_branchs = DB::table('transfer_stock_to_branches')->whereYear('report_date', date('Y'))->get();
        $transfer_to_warehouses = DB::table('transfer_stock_to_warehouses')->whereYear('report_date', date('Y'))->get();
        return view(
            'reports.profit_loss_report.ajax_view.sale_purchase_and_profit_view',
            compact(
                'opening_stocks',
                'stock_adjustments',
                'purchases',
                'sales',
                'products',
                'expanses',
                'transfer_to_branchs',
                'transfer_to_warehouses'
            )
        );
    }

    // Filter sale purchase and profit
    public function filterSalePurchaseProfit(Request $request)
    {
        //return  $request->date_range;
        $opening_stocks = '';
        $stock_adjustments = '';
        $purchases = '';
        $sales = '';
        $expanses = '';
        $transfer_to_branchs = '';
        $transfer_to_warehouses = '';

        $opening_stocks_query = DB::table('product_opening_stocks');
        $stock_adjustments_query = DB::table('stock_adjustments');
        $purchases_query = DB::table('purchases');
        $sales_query = DB::table('sales');
        $products = DB::table('products')->join('taxes', 'products.tax_id', 'taxes.id')
            ->select(['products.*', 'taxes.tax_percent'])
            ->get();
        $expanses_query = DB::table('expanses');
        $transfer_to_branchs_query = DB::table('transfer_stock_to_branches');
        $transfer_to_warehouses_query = DB::table('transfer_stock_to_warehouses');

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $opening_stocks_query->where('branch_id', NULL);
                $stock_adjustments_query->where('branch_id', NULL);
                $purchases_query->where('branch_id', NULL);
                $sales_query->where('branch_id', NULL);
                $expanses_query->where('branch_id', NULL);
                $transfer_to_branchs_query->where('branch_id', NULL);
                $transfer_to_warehouses_query->where('branch_id', NULL);
            } else {
                $opening_stocks_query->where('branch_id', $request->branch_id);
                $stock_adjustments_query->where('branch_id', $request->branch_id);
                $purchases_query->where('branch_id', $request->branch_id);
                $sales_query->where('branch_id', $request->branch_id);
                $expanses_query->where('branch_id', $request->branch_id);
                $transfer_to_branchs_query->where('branch_id', $request->branch_id);
                $transfer_to_warehouses_query->where('branch_id', $request->branch_id);
            }
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));

            $opening_stocks_query->whereBetween('created_at', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $stock_adjustments_query->whereBetween('report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $purchases_query->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $sales_query->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $expanses_query->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $transfer_to_branchs_query->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $transfer_to_warehouses_query->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        }

        $opening_stocks = $opening_stocks_query->select('id', 'unit_cost_inc_tax', 'subtotal')->get();
        $stock_adjustments =  $stock_adjustments_query->get();
        $purchases = $purchases_query->get();
        $sales = $sales_query->get();
        $expanses = $expanses_query->get();
        $transfer_to_branchs = $transfer_to_branchs_query->get();
        $transfer_to_warehouses = $transfer_to_warehouses_query->get();

        return view(
            'reports.profit_loss_report.ajax_view.filtered_sale_purchase_and_profit_view',
            compact(
                'opening_stocks',
                'stock_adjustments',
                'purchases',
                'sales',
                'products',
                'expanses',
                'transfer_to_branchs',
                'transfer_to_warehouses'
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
            }else {
                $invoices = Sale::with(['sale_products'])->where('status', 1)
                    ->whereYear('report_date', date('Y'))->get();
                return view('reports.profit_loss_report.ajax_view.profit_by_invoice', compact('invoices'));
            }
        }
    }

    // Print Profit Loss method
    public function printProfitLoss(Request $request)
    {
        $opening_stocks = '';
        $stock_adjustments = '';
        $purchases = '';
        $sales = '';
        $expanses = '';
        $transfer_to_branchs = '';
        $transfer_to_warehouses = '';
        $fromDate = '';
        $toDate = '';
        $branch_id = $request->branch_id;

        $opening_stocks_query = DB::table('product_opening_stocks');
        $stock_adjustments_query = DB::table('stock_adjustments');
        $purchases_query = DB::table('purchases');
        $sales_query = DB::table('sales');
        $products = DB::table('products')->join('taxes', 'products.tax_id', 'taxes.id')
            ->select(['products.*', 'taxes.tax_percent'])
            ->get();
        $expanses_query = DB::table('expanses');
        $transfer_to_branchs_query = DB::table('transfer_stock_to_branches');
        $transfer_to_warehouses_query = DB::table('transfer_stock_to_warehouses');

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $opening_stocks_query->where('branch_id', NULL);
                $stock_adjustments_query->where('branch_id', NULL);
                $purchases_query->where('branch_id', NULL);
                $sales_query->where('branch_id', NULL);
                $expanses_query->where('branch_id', NULL);
                $transfer_to_branchs_query->where('branch_id', NULL);
                $transfer_to_warehouses_query->where('branch_id', NULL);
            } else {
                $opening_stocks_query->where('branch_id', $request->branch_id);
                $stock_adjustments_query->where('branch_id', $request->branch_id);
                $purchases_query->where('branch_id', $request->branch_id);
                $sales_query->where('branch_id', $request->branch_id);
                $expanses_query->where('branch_id', $request->branch_id);
                $transfer_to_branchs_query->where('branch_id', $request->branch_id);
                $transfer_to_warehouses_query->where('branch_id', $request->branch_id);
            }
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));

            $fromDate = date('Y-m-d', strtotime($date_range[0]));
            $toDate = date('Y-m-d', strtotime($date_range[1]));

            $opening_stocks_query->whereBetween('created_at', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $stock_adjustments_query->whereBetween('report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $purchases_query->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $sales_query->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $expanses_query->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $transfer_to_branchs_query->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $transfer_to_warehouses_query->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        }

        $opening_stocks = $opening_stocks_query->select('id', 'unit_cost_inc_tax', 'subtotal')->get();
        $stock_adjustments =  $stock_adjustments_query->get();
        $purchases = $purchases_query->get();
        $sales = $sales_query->get();
        $expanses = $expanses_query->get();
        $transfer_to_branchs = $transfer_to_branchs_query->get();
        $transfer_to_warehouses = $transfer_to_warehouses_query->get();
        return view(
            'reports.profit_loss_report.ajax_view.printProfitLoss',
            compact(
                'opening_stocks',
                'stock_adjustments',
                'purchases',
                'sales',
                'products',
                'expanses',
                'transfer_to_branchs',
                'transfer_to_warehouses',
                'branch_id',
                'fromDate',
                'toDate',
            )
        );
    }
}
