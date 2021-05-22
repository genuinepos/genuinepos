<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;

class StockAdjustmentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of Stock report
    public function index()
    {
        
        return view('reports.adjustment_report.index');
    }

    // All Stock Adjustment **requested by ajax**
    public function allAdjustmentAmount()
    {
        $adjustments = StockAdjustment::with(['admin', 'branch',])->whereYear('report_date_ts', date('Y'))->get();
        return view('reports.adjustment_report.ajax_view.adjustment_amounts', compact('adjustments'));
    }

    public function filterAdjustment(Request $request)
    {
        //return  $request->date_range;
        $adjustments = '';
        $adjustment_query = StockAdjustment::with(['admin', 'branch',]);

        if ($request->branch_id) {
            $adjustment_query->where('branch_id', $request->branch_id);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            //$form_date = date('Y-m-d', strtotime($date_range[0] . ' -1 days'));
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $adjustment_query->whereBetween('report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        } else {
            $adjustment_query->whereYear('report_date_ts', date('Y'));
        }

        $adjustments = $adjustment_query->get();
        return view('reports.adjustment_report.ajax_view.adjustment_amounts', compact('adjustments'));
    }
}
