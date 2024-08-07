<?php

namespace App\Services\Hrm\Reports;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use Yajra\DataTables\Facades\DataTables;

class PayrollReportService
{
    public function payrollReportTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $payrolls = $this->query(request: $request);

        return DataTables::of($payrolls)
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->branch_area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })
            ->editColumn('voucher_no', function ($row) {
                return '<a href="' . route('hrm.payrolls.show', [$row->id]) . '" id="details_btn">' . $row->voucher_no . '</a>';
            })
            ->editColumn('user', function ($row) {
                $empId = $row->user_emp_id ? ' (' . $row->user_emp_id . ')' : '';
                return $row->user_prefix . ' ' . $row->user_name . ' ' . $row->user_last_name .  $empId;
            })
            ->editColumn('month_year', function ($row) {
                return $row->month . '-' . $row->year;
            })
            ->editColumn('payment_status', function ($row) {
                $html = '';
                if ($row->due <= 0) {

                    $html = '<span class="text-success">' . __("Paid") . '</span>';
                } elseif ($row->due > 0 && $row->due < $row->gross_amount) {

                    $html = '<span class="text-primary">' . __("Partial") . '</span>';
                } elseif ($row->gross_amount == $row->due) {

                    $html = '<span class="text-danger">' . __("Due") . '</span>';
                }

                return $html;
            })
            ->editColumn('total_amount', function ($row) {

                return '<span class="total_amount" data-value="' . $row->total_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_amount) . '</span>';
            })
            ->editColumn('total_allowance', function ($row) {

                return '<span class="total_allowance" data-value="' . $row->total_allowance . '">' . \App\Utils\Converter::format_in_bdt($row->total_allowance) . '</span>';
            })
            ->editColumn('total_deduction', function ($row) {

                return '<span class="total_deduction" data-value="' . $row->total_deduction . '">' . \App\Utils\Converter::format_in_bdt($row->total_deduction) . '</span>';
            })
            ->editColumn('gross_amount', function ($row) {

                return '<span class="gross_amount" data-value="' . $row->gross_amount . '">' . \App\Utils\Converter::format_in_bdt($row->gross_amount) . '</span>';
            })
            ->editColumn('paid', function ($row) {

                return '<span class="paid text-success" data-value="' . $row->paid . '">' . \App\Utils\Converter::format_in_bdt($row->paid) . '</span>';
            })
            ->editColumn('due', function ($row) {

                return '<span class="due text-danger" data-value="' . $row->due . '">' . \App\Utils\Converter::format_in_bdt($row->due) . '</span>';
            })
            ->rawColumns(['action', 'voucher_no', 'branch', 'user', 'total_amount', 'total_allowance', 'total_deduction', 'gross_amount', 'paid', 'due', 'payment_status', 'month_year'])
            ->make(true);
    }

    public function query(object $request): object
    {
        $query = DB::table('hrm_payrolls')
            ->leftJoin('branches', 'hrm_payrolls.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('users', 'hrm_payrolls.user_id', 'users.id');

        $this->filter(request: $request, query: $query);

        return $query->select(
            'hrm_payrolls.id',
            'hrm_payrolls.branch_id',
            'hrm_payrolls.voucher_no',
            'hrm_payrolls.duration_unit',
            'hrm_payrolls.total_amount',
            'hrm_payrolls.total_allowance',
            'hrm_payrolls.total_deduction',
            'hrm_payrolls.gross_amount',
            'hrm_payrolls.month',
            'hrm_payrolls.year',
            'hrm_payrolls.paid',
            'hrm_payrolls.due',
            'hrm_payrolls.date',
            'hrm_payrolls.date_ts',
            'users.prefix as user_prefix',
            'users.name as user_name',
            'users.last_name as user_last_name',
            'users.emp_id as user_emp_id',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
        )->orderBy('hrm_payrolls.id', 'desc');
    }

    private function filter(object $request, object $query): object
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('hrm_payrolls.branch_id', null);
            } else {

                $query->where('hrm_payrolls.branch_id', $request->branch_id);
            }
        }

        if ($request->month_year) {

            $month_year = explode('-', $request->month_year);
            $year = $month_year[0];
            $dateTime = \DateTime::createFromFormat('m', $month_year[1]);
            $month = $dateTime->format('F');

            $query->where('hrm_payrolls.month', $month)->where('hrm_payrolls.year', $year);
        }

        if ($request->department_id && $request->department_id != 'all') {

            $query->where('users.department_id', $request->department_id);
        }

        if ($request->user_id) {

            $query->where('hrm_payrolls.user_id', $request->user_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('hrm_payrolls.date_ts', $date_range); // Final
        }

        // if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('hrm_payrolls.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
