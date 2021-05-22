<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PurchasePaymentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of purchase payment report
    public function index()
    {
        return view('reports.purchase_payment_report.index');
    }

    // Get purchase payment reports
    public function getPurchasePaymentReport(Request $request)
    {
        $payments = '';
        $query = DB::table('purchase_payments')
            ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
            ->join('suppliers', 'purchases.supplier_id', 'suppliers.id');

        if ($request->supplier_id) {
            $query->where('purchases.supplier_id', $request->supplier_id);
        }

        if ($request->branch_id) {
            $query->where('purchases.branch_id', $request->branch_id);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            //$form_date = date('Y-m-d', strtotime($date_range[0] . ' -1 days'));
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $query->whereBetween('purchase_payments.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        } else {
            $query->whereYear('purchase_payments.report_date', date('Y'));
        }

        $payments = $query->select(
            'purchase_payments.id as payment_id',
            'purchase_payments.invoice_id as payment_invoice',
            'purchase_payments.paid_amount',
            'purchase_payments.pay_mode',
            'purchase_payments.date',
            'purchases.invoice_id as purchase_invoice',
            'suppliers.name as supplier_name',
        )->get();

        return view('reports.purchase_payment_report.ajax_view.payment_list', compact('payments'));
    }
}
