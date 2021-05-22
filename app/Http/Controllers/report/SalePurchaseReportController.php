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
        return view('reports.sale_purchase_report.index');
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
        //return  $request->date_range;
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

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            //$form_date = date('Y-m-d', strtotime($date_range[0] . ' -1 days'));
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));

            $sale_query->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $purchase_query->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        }

        $sales = $sale_query->get();
        $purchases =  $purchase_query->get();

        return view('reports.sale_purchase_report.ajax_view.filtered_sale_and_purchase_amount', compact('sales', 'purchases'));
    }
}
