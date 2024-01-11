<?php

namespace App\Services\Hrm\MethodContainerServices;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Enums\AllowanceAndDeductionType;
use App\Interfaces\Hrm\PayrollControllerMethodContainersInterface;

class PayrollControllerMethodContainersService implements PayrollControllerMethodContainersInterface
{
    public function showMethodContainer(
        int $id,
        object $payrollService,
    ): ?array {

        $data = [];
        $data['payroll'] = $payrollService->singlePayroll(
            with: [
                'branch',
                'branch.parentBranch',
                'user',
                'allowances',
                'allowances.allowance',
                'deductions',
                'deductions.deduction',

                'references:id,voucher_description_id,payroll_id,amount',
                'references.voucherDescription:id,accounting_voucher_id',
                'references.voucherDescription.accountingVoucher:id,voucher_no,date,date_ts,voucher_type',
                'references.voucherDescription.accountingVoucher.voucherDescriptions:id,accounting_voucher_id,account_id,payment_method_id',
                'references.voucherDescription.accountingVoucher.voucherDescriptions.paymentMethod:id,name',
                'references.voucherDescription.accountingVoucher.voucherDescriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
                'references.voucherDescription.accountingVoucher.voucherDescriptions.account.bank:id,name',
                'references.voucherDescription.accountingVoucher.voucherDescriptions.account.group:id,sub_sub_group_number',
            ]
        )->where('id', $id)->first();

        return $data;
    }

    public function createMethodContainer(object $request, object $payrollService, object $accountService, object $userService): ?array
    {
        $data = [];
        $month_year = explode('-', $request->month_year);
        $data['year'] = $month_year[0];
        $dateTime = \DateTime::createFromFormat('m', $month_year[1]);
        $data['month'] = $dateTime->format('F');

        $data['payroll'] = $payrollService->singlePayroll()->where('user_id', $request->user_id)->where('month', $data['month'])->where('year', $data['year'])->first();

        if (isset($data['payroll'])) {

            return $data;
        }

        $data['user'] = $userService->singleUser(id: $request->user_id);
        $data['attendances'] = DB::table('hrm_attendances')->where('user_id', $request->user_id)->where('month', $data['month'])->where('year', $data['year'])->get();

        $data['totalHours'] = 0;
        $data['totalPresent'] = 0;
        foreach ($data['attendances'] as $attendance) {

            if ($attendance->is_completed == BooleanType::True->value) {

                $startTime = Carbon::parse($attendance->clock_in_ts);
                $endTime = Carbon::parse($attendance->clock_out_ts);
                $totalSeconds = $startTime->diffInSeconds($endTime);
                $minutes = $totalSeconds / 60;
                $hours = $minutes / 60;
                $data['totalHours'] += $hours;
            }

            $data['totalPresent'] += 1;
        }

        $data['expenseAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        $data['allowances'] = DB::table('hrm_allowances')->where('type', AllowanceAndDeductionType::Allowance->value)->get();
        $data['deductions'] = DB::table('hrm_allowances')->where('type', AllowanceAndDeductionType::Deduction->value)->get();

        return $data;
    }

    public function storeMethodContainer(
        object $request,
        object $payrollService,
        object $payrollAllowanceService,
        object $payrollDeductionService,
        object $dayBookService,
        object $codeGenerator,
    ): void {

        $generalSettings = config('generalSettings');
        $payrollVoucherPrefix = $generalSettings['prefix__payroll_voucher_prefix'] ? $generalSettings['prefix__payroll_voucher_prefix'] : 'PRL';
        $addPayroll = $payrollService->addPayroll(request: $request, payrollVoucherPrefix: $payrollVoucherPrefix, codeGenerator: $codeGenerator);
        $payrollAllowanceService->addPayrollAllowances(request: $request, payroll: $addPayroll);
        $payrollDeductionService->addPayrollDeductions(request: $request, payroll: $addPayroll);

        // Add Day Book entry for Payroll
        $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Payroll->value, date: $addPayroll->date, accountId: $addPayroll->expense_account_id, transId: $addPayroll->id, amount: $addPayroll->gross_amount, amountType: 'debit');
    }

    public function editMethodContainer(
        int $id,
        object $payrollService,
        object $accountService,
    ): ?array {

        $data = [];
        $data['payroll'] = $payrollService->singlePayroll(
            with: [
                'user',
                'allowances',
                'allowances.allowance',
                'deductions',
                'deductions.deduction',
            ]
        )->where('id', $id)->first();

        $data['attendances'] = DB::table('hrm_attendances')->where('user_id', $data['payroll']->user_id)->where('month', $data['payroll']->month)->where('year', $data['payroll']->month)->get();

        $data['totalHours'] = 0;
        $data['totalPresent'] = 0;
        foreach ($data['attendances'] as $attendance) {

            if ($attendance->is_completed == BooleanType::True->value) {

                $startTime = Carbon::parse($attendance->clock_in_ts);
                $endTime = Carbon::parse($attendance->clock_out_ts);
                $totalSeconds = $startTime->diffInSeconds($endTime);
                $minutes = $totalSeconds / 60;
                $hours = $minutes / 60;
                $data['totalHours'] += $hours;
            }

            $data['totalPresent'] += 1;
        }

        $data['expenseAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')->get();

        return $data;
    }

    public function updateMethodContainer(
        int $id,
        object $request,
        object $payrollService,
        object $payrollAllowanceService,
        object $payrollDeductionService,
        object $dayBookService,
    ): void {

        $updatePayroll = $payrollService->updatePayroll(request: $request, id: $id);
        $payrollAllowanceService->updatePayrollAllowances(request: $request, payroll: $updatePayroll);
        $payrollDeductionService->updatePayrollDeductions(request: $request, payroll: $updatePayroll);
        $payrollService->adjustPayrollAmounts(payroll: $updatePayroll);

        $dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Payroll->value, date: $updatePayroll->date, accountId: $updatePayroll->expense_account_id, transId: $updatePayroll->id, amount: $updatePayroll->gross_amount, amountType: 'debit', branchId: $updatePayroll->branchId);
    }

    public function deleteMethodContainer(
        int $id,
        object $payrollService,
    ): ?array {

        $deletePayroll = $payrollService->deletePayroll(id: $id);

        if ($deletePayroll['pass'] == false) {

            return ['pass' => false, 'msg' => $deletePayroll['msg']];
        }

        return ['pass' => true];
    }
}
