<?php

namespace App\Services\Hrm;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Models\Hrm\PayrollDeduction;
use Yajra\DataTables\Facades\DataTables;

class PayrollDeductionService
{
    public function addPayrollDeductions(object $request, object $payroll): void
    {
        if (isset($request->deduction_amounts)) {

            foreach ($request->deduction_names as $key => $deduction_name) {

                if (isset($request->deduction_amounts[$key]) && $request->deduction_amounts[$key] > 0) {

                    $addPayrollDeduction = new PayrollDeduction();
                    $addPayrollDeduction->payroll_id = $payroll->id;
                    $addPayrollDeduction->deduction_id = $request->deduction_ids[$key];
                    $addPayrollDeduction->deduction_name = $deduction_name;
                    $addPayrollDeduction->amount_type = $request->deduction_amount_types[$key];
                    $deductionPercent = $request->deduction_percents[$key] ? $request->deduction_percents[$key] : 0;
                    $addPayrollDeduction->deduction_percent = $request->deduction_amount_types[$key] == 2 ? $deductionPercent : 0;
                    $addPayrollDeduction->deduction_amount = $request->deduction_amounts[$key] ? $request->deduction_amounts[$key] : 0;
                    $addPayrollDeduction->save();
                }
            }
        }
    }

    public function updatePayrollDeductions(object $request, object $payroll): void
    {
        foreach ($payroll->deductions as $deduction) {

            $deduction->is_delete_in_update = BooleanType::True->value;
            $deduction->save();
        }

        if (isset($request->deduction_amounts)) {

            foreach ($request->deduction_amounts as $key => $deduction_name) {

                if (isset($request->deduction_amounts[$key]) && $request->deduction_amounts[$key] > 0) {

                    $addOrUpdatePayrollDeduction = '';
                    $payrollDeduction = $this->singlePayrollDeduction(id: $request->payroll_deduction_ids[$key]);

                    if ($payrollDeduction) {
                        $addOrUpdatePayrollDeduction = $payrollDeduction;
                    } else {
                        $addOrUpdatePayrollDeduction = new PayrollDeduction();
                    }

                    $addOrUpdatePayrollDeduction->payroll_id = $payroll->id;
                    $addOrUpdatePayrollDeduction->deduction_id = $request->deduction_ids[$key];
                    $addOrUpdatePayrollDeduction->deduction_name = $deduction_name;
                    $addOrUpdatePayrollDeduction->amount_type = $request->deduction_amount_types[$key];
                    $deductionPercent = $request->deduction_percents[$key] ? $request->deduction_percents[$key] : 0;
                    $addOrUpdatePayrollDeduction->deduction_percent = $request->deduction_amount_types[$key] == 2 ? $deductionPercent : 0;
                    $addOrUpdatePayrollDeduction->deduction_amount = $request->deduction_amounts[$key] ? $request->deduction_amounts[$key] : 0;
                    $addOrUpdatePayrollDeduction->is_delete_in_update = BooleanType::False->value;
                    $addOrUpdatePayrollDeduction->save();
                }
            }
        }

        $unusedDeletablePayrollDeductions = $this->payrollDeductions()->where('payroll_id', $payroll->id)->where('is_delete_in_update', BooleanType::True->value)->get();
        foreach ($unusedDeletablePayrollDeductions as $unusedDeletablePayrollDeduction) {

            $unusedDeletablePayrollDeduction->delete();
        }
    }

    public function singlePayrollDeduction(?int $id, ?array $with = null)
    {
        $query = PayrollDeduction::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function payrollDeductions(?array $with = null)
    {
        $query = PayrollDeduction::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
