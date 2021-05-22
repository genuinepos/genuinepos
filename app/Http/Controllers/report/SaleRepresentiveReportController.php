<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleRepresentiveReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of cash register report
    public function index()
    {
        return view('reports.sale_representive_report.index');
    }

    public function getSaleRepresentiveReport(Request $request)
    {
        $sales = '';
        $expenses = '';
        $sale_query = DB::table('sales')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->where('sales.status', 1);

        $expense_query = DB::table('expanses')
            ->leftJoin('expanse_categories', 'expanses.expanse_category_id', 'expanse_categories.id')
            ->leftJoin('branches', 'expanses.branch_id', 'branches.id')
            ->leftJoin('admin_and_users', 'expanses.admin_id', 'admin_and_users.id');
            

        if ($request->user_id) {
            $sale_query->where('sales.admin_id', $request->user_id);
            $expense_query->where('expanses.admin_id', $request->user_id);
        }

        if ($request->branch_id) {
            $sale_query->where('sales.branch_id', $request->branch_id);
            $expense_query->where('expanses.branch_id', $request->branch_id);
        }

    
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            //$form_date = date('Y-m-d', strtotime($date_range[0] . ' -1 days'));
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $sale_query->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $expense_query->whereBetween('expanses.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        } else {
            $sale_query->whereYear('sales.report_date', date('Y'));
            $expense_query->whereYear('expanses.report_date', date('Y'));
        }

        $sales = $sale_query->select(
            'sales.date',
            'sales.branch_id',
            'sales.customer_id',
            'sales.invoice_id',
            'sales.total_payable_amount',
            'sales.paid',
            'sales.due',
            'sales.sale_return_amount',
            'sales.sale_return_due',
            'sales.status',
            'customers.name as customer_name',
            'branches.name as branch_name',
            'branches.branch_code',
        )->get();

        $expenses = $expense_query->select(
            'expanses.date',
            'expanses.invoice_id',
            'expanses.expanse_category_id',
            'expanses.branch_id',
            'expanses.admin_id',
            'expanses.net_total_amount',
            'expanses.paid',
            'expanses.due',
            'expanses.note',
            'expanse_categories.name as expanse_category_name',
            'branches.name as branch_name',
            'branches.branch_code',
            'admin_and_users.prefix',
            'admin_and_users.name as user_name',
            'admin_and_users.last_name as user_last_name',
        )->get();

        return view('reports.sale_representive_report.ajax_view.representive_reports', compact('sales', 'expenses'));
    }
}
