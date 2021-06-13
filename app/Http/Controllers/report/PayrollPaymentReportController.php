<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PayrollPaymentReportController extends Controller
{
    public function payrollPaymentReport(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $payrollPayments = '';
            $payrollPaymentQ = DB::table('hrm_payroll_payments')
                ->leftJoin('hrm_payrolls', 'hrm_payroll_payments.payroll_id', 'hrm_payrolls.id')
                ->leftJoin('admin_and_users', 'hrm_payrolls.user_id', 'admin_and_users.id')
                ->leftJoin('admin_and_users as paid_by', 'hrm_payroll_payments.admin_id', 'paid_by.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $payrollPaymentQ->where('admin_and_users.branch_id', NULL);
                } else {
                    $payrollPaymentQ->where('admin_and_users.branch_id', $request->branch_id);
                }
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                //$form_date = date('Y-m-d', strtotime($date_range[0]. '-1 days'));
                $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
                //$to_date = date('Y-m-d', strtotime($date_range[1]));
                $payrollPaymentQ->whereBetween('hrm_payroll_payments.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
                //$query->whereDate('report_date', '<=', $form_date.' 00:00:00')->whereDate('report_date', '>=', $to_date.' 00:00:00');
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 1) {
                $payrollPayments = $payrollPaymentQ->select(
                    'hrm_payroll_payments.date',
                    'hrm_payroll_payments.reference_no as voucher_no',
                    'hrm_payroll_payments.paid',
                    'hrm_payroll_payments.pay_mode',
                    'hrm_payrolls.reference_no',
                    'admin_and_users.prefix',
                    'admin_and_users.name',
                    'admin_and_users.last_name',
                    'paid_by.prefix as pb_prefix',
                    'paid_by.name as pb_name',
                    'paid_by.last_name as pb_last_name',
                )->orderBy('hrm_payroll_payments.id', 'desc')->get();
            } else {
                $payrollPayments = $payrollPaymentQ->select(
                    'hrm_payroll_payments.date',
                    'hrm_payroll_payments.reference_no as voucher_no',
                    'hrm_payroll_payments.paid',
                    'hrm_payroll_payments.pay_mode',
                    'hrm_payrolls.reference_no',
                    'admin_and_users.prefix',
                    'admin_and_users.name',
                    'admin_and_users.last_name',
                    'paid_by.prefix as pb_prefix',
                    'paid_by.name as pb_name',
                    'paid_by.last_name as pb_last_name',
                )->where('admin_and_users.branch_id', auth()->user()->branch_id)
                ->orderBy('hrm_payroll_payments.id', 'desc')->get();
            }

            return DataTables::of($payrollPayments)
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('employee', function ($row) {
                    return $row->prefix . ' ' . $row->name . ' ' . $row->last_name;
                })
                ->editColumn('paid', function ($row) use ($generalSettings) {
                    return '<span class="paid" data-value="' . $row->paid . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->paid . '</span>';
                })
                ->editColumn('paid_by', function ($row) {
                    return $row->pb_prefix . ' ' . $row->pb_name . ' ' . $row->pb_last_name;
                })
                ->rawColumns(['date', 'employee', 'paid', 'paid_by'])
                ->make(true);
        }

        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.payroll_payment_report.payroll_payment_report', compact('branches'));
    }

    public function payrollPaymentReportPrint(Request $request)
    {
        $branch_id = '';
        $payrollPayments = '';
        $s_date = '';
        $e_date = '';
        $payrollPaymentQ = DB::table('hrm_payroll_payments')
            ->leftJoin('hrm_payrolls', 'hrm_payroll_payments.payroll_id', 'hrm_payrolls.id')
            ->leftJoin('admin_and_users', 'hrm_payrolls.user_id', 'admin_and_users.id')
            ->leftJoin('admin_and_users as paid_by', 'hrm_payroll_payments.admin_id', 'paid_by.id');

        if ($request->branch_id) {
            $branch_id = $request->branch_id;
            if ($request->branch_id == 'NULL') {
                $payrollPaymentQ->where('admin_and_users.branch_id', NULL);
            } else {
                $payrollPaymentQ->where('admin_and_users.branch_id', $request->branch_id);
            }
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $s_date = date('d-F-Y', strtotime($date_range[0]));
            //$form_date = date('Y-m-d', strtotime($date_range[0]. '-1 days'));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $e_date = date('d-F-Y', strtotime($date_range[1]));
            //$to_date = date('Y-m-d', strtotime($date_range[1]));
            $payrollPaymentQ->whereBetween('hrm_payroll_payments.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
            //$query->whereDate('report_date', '<=', $form_date.' 00:00:00')->whereDate('report_date', '>=', $to_date.' 00:00:00');
        }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 1) {
                $payrollPayments = $payrollPaymentQ->select(
                    'hrm_payroll_payments.date',
                    'hrm_payroll_payments.reference_no as voucher_no',
                    'hrm_payroll_payments.paid',
                    'hrm_payroll_payments.pay_mode',
                    'hrm_payrolls.reference_no',
                    'admin_and_users.prefix',
                    'admin_and_users.name',
                    'admin_and_users.last_name',
                    'admin_and_users.emp_id',
                    'paid_by.prefix as pb_prefix',
                    'paid_by.name as pb_name',
                    'paid_by.last_name as pb_last_name',
                )->orderBy('hrm_payroll_payments.id', 'desc')->get();
            } else {
                $payrollPayments = $payrollPaymentQ->select(
                    'hrm_payroll_payments.date',
                    'hrm_payroll_payments.reference_no as voucher_no',
                    'hrm_payroll_payments.paid',
                    'hrm_payroll_payments.pay_mode',
                    'hrm_payrolls.reference_no',
                    'admin_and_users.prefix',
                    'admin_and_users.name',
                    'admin_and_users.last_name',
                    'admin_and_users.emp_id',
                    'paid_by.prefix as pb_prefix',
                    'paid_by.name as pb_name',
                    'paid_by.last_name as pb_last_name',
                )->where('admin_and_users.branch_id', auth()->user()->branch_id)
                ->orderBy('hrm_payroll_payments.id', 'desc')->get();
            }

        return view('reports.payroll_payment_report.ajax_view.payroll_payment_report_print', compact('payrollPayments','branch_id', 's_date', 'e_date'));
    }
}
