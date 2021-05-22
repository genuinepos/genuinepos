<?php

namespace App\Http\Controllers\report;

use App\Charts\CommonChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Expanse;

class ExpanseReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of expense report
    public function index()
    {
        return view('reports.expense_report.index');
    }

    // Get expense report of current year
    public function getExpenseReport()
    {
        $expanseCateogries = DB::table('expanse_categories')->get();
        $labels = [];
        $values = [];
        foreach ($expanseCateogries as $expanseCateogory) {
            $labels[] = $expanseCateogory->name;
            $expenses = DB::table('expanses')->where('expanse_category_id', $expanseCateogory->id)
                ->whereYear('report_date', date('Y'))->select(['net_total_amount'])->get();
            $total_amount = 0;
            foreach ($expenses as $expense) {
                $total_amount += $expense->net_total_amount;
            }
            $values[] = $total_amount;
        }

        $chart = new CommonChart();
        $chart->labels($labels)
            ->dataset('Total Expanse', 'column', $values);

        return view('reports.expense_report.ajax_view.expense_report', compact('chart', 'labels', 'values'));
    }

    public function getFilteredExpenseReport(Request $request)
    {
        if ($request->ex_category_id && $request->branch_id && $request->date_range) {
            $this->getExpenseReport();
        }

        $expenses = Expanse::orderBy('id', 'DESC');
        if ($request->branch_id) {
            $expenses->where('branch_id', $request->branch_id);
        }

        if ($request->ex_category_id) {
            $expenses->where('expanse_category_id', $request->ex_category_id);
        }
    }
}
