<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SalePaymentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of purchase payment report
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $payments = '';
            $query = DB::table('sale_payments')
                ->leftJoin('sales', 'sale_payments.sale_id', 'sales.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id');

            if ($request->customer_id) {
                if ($request->customer_id == 'NULL') {
                    $query->where('sales.customer_id', NULL);
                } else {
                    $query->where('sales.customer_id', $request->customer_id);
                }
            }

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('sales.branch_id', NULL);
                } else {
                    $query->where('sales.branch_id', $request->branch_id);
                }
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

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $payments = $query->select(
                    'sale_payments.id as payment_id',
                    'sale_payments.invoice_id as payment_invoice',
                    'sale_payments.paid_amount',
                    'sale_payments.pay_mode',
                    'sale_payments.date',
                    'sales.invoice_id as sale_invoice',
                    'customers.name as customer_name',
                )->get();
            }else {
                $payments = $query->select(
                    'sale_payments.id as payment_id',
                    'sale_payments.invoice_id as payment_invoice',
                    'sale_payments.paid_amount',
                    'sale_payments.pay_mode',
                    'sale_payments.date',
                    'sales.invoice_id as sale_invoice',
                    'customers.name as customer_name',
                )->where('sales.branch_id', auth()->user()->branch_id)->get();
            }
            
            return DataTables::of($payments)
            ->editColumn('date', function ($row) {
                return date('d/m/Y', strtotime($row->date));
            })
            ->editColumn('customer_name',  function ($row) {
                return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
            })
            ->editColumn('paid_amount',  function ($row) use ($generalSettings) {
                return '<b><span class="paid_amount" data-value="'.$row->paid_amount.'">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->paid_amount . '</span></b>';
            })
            ->rawColumns(['date', 'customer_name', 'paid_amount'])
            ->make(true);
        }
        return view('reports.sale_payment_report.index');
    }
}
