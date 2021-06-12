<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PayrollReportController extends Controller
{
    public function payrollReport(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $payrolls = '';
            $query = DB::table('hrm_payrolls')
                ->leftJoin('admin_and_users', 'hrm_payrolls.user_id', 'admin_and_users.id')
                ->leftJoin('hrm_department', 'admin_and_users.department_id', 'hrm_department.id')
                ->leftJoin('admin_and_users as created_by', 'hrm_payrolls.admin_id', 'created_by.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('admin_and_users.branch_id', NULL);
                } else {
                    $query->where('admin_and_users.branch_id', $request->branch_id);
                }
            }

            if ($request->department_id) {
                $query->where('admin_and_users.department_id', $request->department_id);
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                //$form_date = date('Y-m-d', strtotime($date_range[0]. '-1 days'));
                $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
                //$to_date = date('Y-m-d', strtotime($date_range[1]));
                $query->whereBetween('hrm_payrolls.report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
                //$query->whereDate('report_date', '<=', $form_date.' 00:00:00')->whereDate('report_date', '>=', $to_date.' 00:00:00');
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 1) {
                $payrolls = $query->select(
                    'hrm_payrolls.*',
                    'admin_and_users.prefix as emp_prefix',
                    'admin_and_users.name as emp_name',
                    'admin_and_users.last_name as emp_last_name',
                    'admin_and_users.branch_id',
                    'hrm_department.department_name',
                    'created_by.prefix as user_prefix',
                    'created_by.name as user_name',
                    'created_by.last_name as user_last_name',
                )->get();
            } else {
                $payrolls = $query->select(
                    'hrm_payrolls.*',
                    'admin_and_users.prefix as emp_prefix',
                    'admin_and_users.name as emp_name',
                    'admin_and_users.last_name as emp_last_name',
                    'admin_and_users.branch_id',
                    'hrm_department.department_name',
                    'created_by.prefix as user_prefix',
                    'created_by.name as user_name',
                    'created_by.last_name as user_last_name',
                )->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
            }

            return DataTables::of($payrolls)
                ->addIndexColumn()
                ->editColumn('employee', function ($row) {
                    return $row->emp_prefix . ' ' . $row->emp_name . ' ' . $row->emp_last_name;
                })
                ->editColumn('month_year', function ($row) {
                    return $row->month . '/' . $row->year;
                })
                ->editColumn('payment_status', function ($row) {
                    $html = '';
                    if ($row->due <= 0) {
                        $html = '<span class="badge bg-success">Paid</span>';
                    } elseif ($row->due > 0 && $row->due < $row->gross_amount) {
                        $html = '<span class="badge bg-primary text-white">Partial</span>';
                    } elseif ($row->gross_amount == $row->due) {
                        $html = '<span class="badge bg-danger text-white">Due</span>';
                    }
                    return $html;
                })
                ->editColumn('gross_amount', function ($row) use ($generalSettings) {
                    return '<span class="gross_amount" data-value="' . $row->gross_amount . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->gross_amount . '</span>';
                })
                ->editColumn('paid', function ($row) use ($generalSettings) {
                    return '<span class="paid" data-value="' . $row->paid . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->paid . '</span>';
                })
                ->editColumn('due', function ($row) use ($generalSettings) {
                    return '<span class="due" data-value="' . $row->due . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->due . '</span>';
                })
                ->editColumn('created_by', function ($row) {
                    return $row->user_prefix . ' ' . $row->user_name . ' ' . $row->user_last_name;
                })
                ->rawColumns(['employee', 'month_year', 'payment_status', 'gross_amount', 'paid', 'due', 'created_by'])
                ->make(true);
        }

        $departments = DB::table('hrm_department')->get(['id', 'department_name']);
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.payroll_report.payroll_report', compact('branches', 'departments'));
    }

    public function payrollReportPrint(Request $request)
    {
        $payrolls = '';
        $s_date = '';
        $e_date = '';
        $branch_id = '';
        $query = DB::table('hrm_payrolls')
            ->leftJoin('admin_and_users', 'hrm_payrolls.user_id', 'admin_and_users.id')
            ->leftJoin('hrm_department', 'admin_and_users.department_id', 'hrm_department.id')
            ->leftJoin('admin_and_users as created_by', 'hrm_payrolls.admin_id', 'created_by.id');

        if ($request->branch_id) {
            $branch_id = $request->branch_id;
            if ($request->branch_id == 'NULL') {
                $query->where('admin_and_users.branch_id', NULL);
            } else {
                $query->where('admin_and_users.branch_id', $request->branch_id);
            }
        }

        if ($request->department_id) {
            $query->where('admin_and_users.department_id', $request->department_id);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $s_date = date('d/m/Y', strtotime($date_range[0]));
            //$form_date = date('Y-m-d', strtotime($date_range[0]. '-1 days'));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $e_date = date('d/m/Y', strtotime($date_range[1]));
            //$to_date = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('hrm_payrolls.report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
            //$query->whereDate('report_date', '<=', $form_date.' 00:00:00')->whereDate('report_date', '>=', $to_date.' 00:00:00');
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 1) {
            $payrolls = $query->select(
                'hrm_payrolls.*',
                'admin_and_users.prefix as emp_prefix',
                'admin_and_users.name as emp_name',
                'admin_and_users.last_name as emp_last_name',
                'admin_and_users.emp_id',
                'admin_and_users.branch_id',
                'hrm_department.department_name',
                'created_by.prefix as user_prefix',
                'created_by.name as user_name',
                'created_by.last_name as user_last_name',
            )->get();
        } else {
            $payrolls = $query->select(
                'hrm_payrolls.*',
                'admin_and_users.prefix as emp_prefix',
                'admin_and_users.name as emp_name',
                'admin_and_users.last_name as emp_last_name',
                'admin_and_users.emp_id',
                'admin_and_users.branch_id',
                'hrm_department.department_name',
                'created_by.prefix as user_prefix',
                'created_by.name as user_name',
                'created_by.last_name as user_last_name',
            )->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
        }

        return view('reports.payroll_report.ajax_view.payroll_report_print', compact('payrolls', 's_date', 'e_date', 'branch_id'));
    }
}
