<?php

namespace App\Services\Hrm;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Models\Hrm\PayrollAllowance;
use Yajra\DataTables\Facades\DataTables;

class PayrollAllowanceService
{
    public function addPayrollAllowances(object $request, object $payroll): void
    {
        if (isset($request->allowance_amounts)) {

            foreach ($request->allowance_names as $key => $allowance_name) {

                if (isset($request->allowance_amounts[$key]) && $request->allowance_amounts[$key] > 0) {

                    $addPayrollAllowance = new PayrollAllowance();
                    $addPayrollAllowance->payroll_id = $payroll->id;
                    $addPayrollAllowance->allowance_id = $request->allowance_ids[$key];
                    $addPayrollAllowance->allowance_name = $allowance_name;
                    $addPayrollAllowance->amount_type = $request->allowance_amount_types[$key];
                    $allowancePercent = $request->allowance_percents[$key] ? $request->allowance_percents[$key] : 0;
                    $addPayrollAllowance->allowance_percent = $request->allowance_amount_types[$key] == 2 ? $allowancePercent : 0;
                    $addPayrollAllowance->allowance_amount = $request->allowance_amounts[$key] ? $request->allowance_amounts[$key] : 0;
                    $addPayrollAllowance->save();
                }
            }
        }
    }

    public function updatePayrollAllowances(object $request, object $payroll): void
    {
        foreach ($payroll->allowances as $allowance) {

            $allowance->is_delete_in_update = BooleanType::True->value;
            $allowance->save();
        }

        if (isset($request->allowance_amounts)) {

            foreach ($request->allowance_names as $key => $allowance_name) {

                if (isset($request->allowance_amounts[$key]) && $request->allowance_amounts[$key] > 0) {

                    $addOrUpdatePayrollAllowance = '';
                    $payrollAllowance = $this->singlePayrollAllowance(id: $request->payroll_allowance_ids[$key]);

                    if ($payrollAllowance) {
                        $addOrUpdatePayrollAllowance = $payrollAllowance;
                    } else {
                        $addOrUpdatePayrollAllowance = new PayrollAllowance();
                    }

                    $addOrUpdatePayrollAllowance->payroll_id = $payroll->id;
                    $addOrUpdatePayrollAllowance->allowance_id = $request->allowance_ids[$key];
                    $addOrUpdatePayrollAllowance->allowance_name = $allowance_name;
                    $addOrUpdatePayrollAllowance->amount_type = $request->allowance_amount_types[$key];
                    $allowancePercent = $request->allowance_percents[$key] ? $request->allowance_percents[$key] : 0;
                    $addOrUpdatePayrollAllowance->allowance_percent = $request->allowance_amount_types[$key] == 2 ? $allowancePercent : 0;
                    $addOrUpdatePayrollAllowance->allowance_amount = $request->allowance_amounts[$key] ? $request->allowance_amounts[$key] : 0;
                    $addOrUpdatePayrollAllowance->is_delete_in_update = BooleanType::False->value;
                    $addOrUpdatePayrollAllowance->save();
                }
            }
        }

        $unusedDeletablePayrollAllowances = $this->payrollAllowances()->where('payroll_id', $payroll->id)->where('is_delete_in_update', BooleanType::True->value)->get();
        foreach ($unusedDeletablePayrollAllowances as $unusedDeletablePayrollAllowance) {

            $unusedDeletablePayrollAllowance->delete();
        }
    }

    public function singlePayrollAllowance(?int $id, ?array $with = null)
    {
        $query = PayrollAllowance::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function payrollAllowances(?array $with = null)
    {
        $query = PayrollAllowance::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
