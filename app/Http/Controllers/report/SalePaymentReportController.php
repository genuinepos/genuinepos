<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SalePaymentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of purchase payment report
    public function index()
    {
        return view('reports.sale_payment_report.index');
    }

    // Get purchase payment reports
    public function getSalePaymentReport(Request $request)
    {
        //return $request->all();
        $payments = '';
        $query = DB::table('sale_payments')
            ->leftJoin('sales', 'sale_payments.sale_id', 'sales.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id');

        if ($request->customer_id) {
            if ($request->customer_id == 'NULL') {
                $query->where('sale_payments.customer_id', NULL);
            }else {
                $query->where('sale_payments.customer_id', $request->customer_id);
            }
        }

        if ($request->branch_id) {
            $query->where('sales.branch_id', $request->branch_id);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            //$form_date = date('Y-m-d', strtotime($date_range[0] . ' -1 days'));
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $query->whereBetween('sale_payments.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        } else {
            $query->whereYear('sale_payments.report_date', date('Y'));
        }

        $payments = $query->select(
            'sale_payments.id as payment_id',
            'sale_payments.invoice_id as payment_invoice',
            'sale_payments.paid_amount',
            'sale_payments.pay_mode',
            'sale_payments.date',
            'sales.invoice_id as sale_invoice',
            'customers.name as customer_name',
        )->get();

        return view('reports.sale_payment_report.ajax_view.payment_list', compact('payments'));
    }
}
