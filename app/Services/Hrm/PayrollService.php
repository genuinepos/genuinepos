<?php

namespace App\Services\Hrm;

use App\Enums\RoleType;
use App\Enums\BooleanType;
use App\Models\Hrm\Payroll;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PayrollService
{
    public function payrollsTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $payrolls = '';
        $query = DB::table('hrm_payrolls')
            ->leftJoin('branches', 'hrm_payrolls.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('users', 'hrm_payrolls.user_id', 'users.id')
            ->leftJoin('hrm_departments', 'users.department_id', 'hrm_departments.id')
            ->leftJoin('users as createdBy', 'hrm_payrolls.created_by_id', 'createdBy.id');

        $this->filter(request: $request, query: $query);

        $payrolls = $query->select(
            'hrm_payrolls.id',
            'hrm_payrolls.branch_id',
            'hrm_payrolls.voucher_no',
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
            'hrm_departments.name as department_name',
            'createdBy.prefix as created_by_prefix',
            'createdBy.name as created_by_name',
            'createdBy.last_name as created_by_last_name',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
        )->orderBy('hrm_payrolls.id', 'desc');

        return DataTables::of($payrolls)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __("Action") . '</button>';

                $html .= '<div class="dropdown-menu">';
                $html .= '<a href="' . route('hrm.payrolls.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __("View") . '</a>';

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('payroll_payments_edit') && $row->due > 0) {

                        $html .= '<a href="#" class="dropdown-item" id="add_payment">' . __("Add Payment") . '</a>';
                    }

                    if (auth()->user()->can('payrolls_edit')) {

                        $html .= '<a href="' . route('hrm.payrolls.edit', [$row->id]) . '" class="dropdown-item" id="edit">' . __("Edit") . '</a>';
                    }

                    if (auth()->user()->can('payrolls_delete')) {

                        $html .= '<a href="' . route('hrm.payrolls.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __("Delete") . '</a>';
                    }
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->branch_area_name . ')';
                    }
                } else {

                    return $generalSettings['business__business_name'];
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
            ->editColumn('gross_amount', function ($row) {

                return \App\Utils\Converter::format_in_bdt($row->gross_amount);
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
            ->editColumn('created_by', function ($row) {

                return $row->created_by_prefix . ' ' . $row->created_by_name . ' ' . $row->created_by_last_name;
            })
            ->rawColumns(['action', 'voucher_no', 'branch', 'user', 'gross_amount', 'paid', 'due', 'payment_status', 'month_year', 'created_by'])
            ->make(true);
    }

    public function addPayroll(object $request, object $codeGenerator): object
    {
        $voucherNo = $codeGenerator->generateMonthWise(table: 'hrm_payrolls', column: 'voucher_no', prefix: 'PR', splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        $addPayroll = new Payroll();
        $addPayroll->voucher_no = $voucherNo;
        $addPayroll->branch_id = auth()->user()->branch_id;
        $addPayroll->user_id = $request->user_id;
        $addPayroll->expense_account_id = $request->expense_account_id;
        $addPayroll->duration_time = $request->duration_time;
        $addPayroll->duration_unit = $request->duration_unit;
        $addPayroll->amount_per_unit = $request->amount_per_unit;
        $addPayroll->total_amount = $request->total_amount;
        $addPayroll->total_allowance = $request->total_allowance;
        $addPayroll->total_deduction = $request->total_deduction;
        $addPayroll->gross_amount = $request->gross_amount;
        $addPayroll->due = $request->gross_amount;
        $addPayroll->date_ts = date('Y-m-d H:i:s');
        $addPayroll->date = date('d-m-Y');
        $addPayroll->month = $request->month;
        $addPayroll->year = $request->year;
        $addPayroll->created_by_id = auth()->user()->id;
        $addPayroll->save();

        return $addPayroll;
    }

    public function updatePayroll(object $request, int $id): object
    {
        $updatePayroll = $this->singlePayroll(with: ['allowances', 'deductions'])->where('id', $id)->first();

        $updatePayroll->expense_account_id = $request->expense_account_id;
        $updatePayroll->duration_time = $request->duration_time;
        $updatePayroll->duration_unit = $request->duration_unit;
        $updatePayroll->amount_per_unit = $request->amount_per_unit;
        $updatePayroll->total_amount = $request->total_amount;
        $updatePayroll->total_allowance = $request->total_allowance;
        $updatePayroll->total_deduction = $request->total_deduction;
        $updatePayroll->gross_amount = $request->gross_amount;
        $updatePayroll->save();

        return $updatePayroll;
    }

    public function deletePayroll(int $id): array|object
    {
        $deletePayroll = $this->singlePayroll()->where('id', $id)->first();

        if (isset($deletePayroll)) {

            $deletePayroll->delete();
        }

        return $deletePayroll;
    }

    public function singlePayroll(?array $with = null)
    {
        $query = Payroll::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function storeAndUpdateValidation(object $request): ?array
    {
        return $request->validate([
            'expense_account_id' => 'required',
            'amount_per_unit' => 'required',
            'duration_time' => 'required',
            'duration_unit' => 'required',
        ]);
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

            $query->where('month', $month)->where('year', $year);
        }

        if ($request->user_id) {

            $query->where('hrm_payrolls.user_id', $request->user_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $query->whereBetween('hrm_payrolls.date_ts', $date_range); // Final
        }

        if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('hrm_payrolls.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
