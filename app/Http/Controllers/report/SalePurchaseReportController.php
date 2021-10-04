<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SalePurchaseReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of sale & purchase report
    public function index()
    {
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.sale_purchase_report.index', compact('branches'));
    }

    // Get sale purchase amounts **requested by ajax**
    public function salePurchaseAmounts()
    {
        $sales = DB::table('sales')->whereYear('report_date', date('Y'))->get();
        $purchases = DB::table('purchases')->whereYear('report_date', date('Y'))->get();
        return view('reports.sale_purchase_report.ajax_view.sale_and_purchase_amount', compact('sales', 'purchases'));
    }

    // Get sale purchase amounts **requested by ajax**
    public function filterSalePurchaseAmounts(Request $request)
    {
        $opening_stocks = '';
        $stock_adjustments = '';
        $purchases = '';
        $sales = '';
        $expanses = '';
        $transfer_to_branchs = '';
        $transfer_to_warehouses = '';

        $sales = '';
        $purchases = '';
        $sale_query = DB::table('sales');
        $purchase_query = DB::table('purchases');
        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $sale_query->where('branch_id', NULL);
                $purchase_query->where('branch_id', NULL);
            } else {
                $sale_query->where('branch_id', $request->branch_id);
                $purchase_query->where('branch_id', $request->branch_id);
            }
        }

        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $sale_query->whereBetween('report_date', $date_range);
            $purchase_query->whereBetween('report_date', $date_range);
        }

        $sales = $sale_query->get();
        $purchases =  $purchase_query->get();
        return view('reports.sale_purchase_report.ajax_view.filtered_sale_and_purchase_amount', compact('sales', 'purchases'));
    }

    public function printSalePurchase(Request $request)
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

        $sales = '';
        $purchases = '';
        $sale_query = DB::table('sales');
        $purchase_query = DB::table('purchases');
        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $sale_query->where('branch_id', NULL);
                $purchase_query->where('branch_id', NULL);
            } else {
                $sale_query->where('branch_id', $request->branch_id);
                $purchase_query->where('branch_id', $request->branch_id);
            }
        }

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];

            $sale_query->whereBetween('report_date', $date_range);
            $purchase_query->whereBetween('report_date', $date_range);
        }

        $sales = $sale_query->get();
        $purchases =  $purchase_query->get();
        return view('reports.sale_purchase_report.ajax_view.printSalePurchase', compact('sales', 'purchases', 'fromDate', 'toDate', 'branch_id'));
    }
}
