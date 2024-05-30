<?php

namespace App\Services\Hrm\MethodContainerServices;

use Carbon\Carbon;
use App\Enums\UserType;
use App\Enums\BooleanType;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Services\Users\UserService;
use App\Enums\AccountingVoucherType;
use App\Services\Hrm\PayrollService;
use App\Services\Setups\BranchService;
use App\Enums\ProductLedgerVoucherType;
use App\Services\Hrm\DepartmentService;
use App\Enums\AllowanceAndDeductionType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Hrm\PayrollAllowanceService;
use App\Services\Hrm\PayrollDeductionService;
use App\Interfaces\Hrm\PayrollControllerMethodContainersInterface;

class PayrollControllerMethodContainersService implements PayrollControllerMethodContainersInterface
{
    public function __construct(
        private PayrollService $payrollService,
        private PayrollAllowanceService $payrollAllowanceService,
        private PayrollDeductionService $payrollDeductionService,
        private UserService $userService,
        private DepartmentService $departmentService,
        private BranchService $branchService,
        private AccountService $accountService,
        private DayBookService $dayBookService,
    ) {
    }

    public function indexMethodContainer(object $request): object|array
    {
        $data = [];
        if ($request->ajax()) {

            return $this->payrollService->payrollsTable(request: $request);
        }

        $data['departments'] = $this->departmentService->departments()->get(['id', 'name']);

        $data['users'] = $this->userService->users()
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('user_type', [UserType::Employee->value, UserType::Both->value])
            ->get(['id', 'prefix', 'name', 'last_name', 'emp_id']);

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return $data;
    }

    public function showMethodContainer(int $id): ?array
    {
        $data = [];
        $data['payroll'] = $this->payrollService->singlePayroll(
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

    public function printMethodContainer(int $id, object $request): ?array
    {
        $data = [];
        $data['payroll'] = $this->payrollService->singlePayroll(
            with: [
                'branch',
                'branch.parentBranch',
                'user',
                'allowances',
                'allowances.allowance',
                'deductions',
                'deductions.deduction',
            ]
        )->where('id', $id)->first();

        $data['printPageSize'] = $request->print_page_size;

        return $data;
    }

    public function createMethodContainer(object $request): ?array
    {
        $data = [];
        $month_year = explode('-', $request->month_year);
        $data['year'] = $month_year[0];
        $dateTime = \DateTime::createFromFormat('m', $month_year[1]);
        $data['month'] = $dateTime->format('F');

        $data['payroll'] = $this->payrollService->singlePayroll()->where('user_id', $request->user_id)->where('month', $data['month'])->where('year', $data['year'])->first();

        if (isset($data['payroll'])) {

            return $data;
        }

        $data['user'] = $this->userService->singleUser(id: $request->user_id);
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

        $data['expenseAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        $data['allowances'] = DB::table('hrm_allowances')->where('type', AllowanceAndDeductionType::Allowance->value)->get();
        $data['deductions'] = DB::table('hrm_allowances')->where('type', AllowanceAndDeductionType::Deduction->value)->get();

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): void
    {
        $generalSettings = config('generalSettings');
        $payrollVoucherPrefix = $generalSettings['prefix__payroll_voucher_prefix'] ? $generalSettings['prefix__payroll_voucher_prefix'] : 'PRL';
        $addPayroll = $this->payrollService->addPayroll(request: $request, payrollVoucherPrefix: $payrollVoucherPrefix, codeGenerator: $codeGenerator);
        $this->payrollAllowanceService->addPayrollAllowances(request: $request, payroll: $addPayroll);
        $this->payrollDeductionService->addPayrollDeductions(request: $request, payroll: $addPayroll);

        // Add Day Book entry for Payroll
        $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Payroll->value, date: $addPayroll->date, accountId: $addPayroll->expense_account_id, transId: $addPayroll->id, amount: $addPayroll->gross_amount, amountType: 'debit');
    }

    public function editMethodContainer(int $id): ?array
    {
        $data = [];
        $data['payroll'] = $this->payrollService->singlePayroll(
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

        $data['expenseAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')->get();

        return $data;
    }

    public function updateMethodContainer(int $id, object $request): void
    {
        $updatePayroll = $this->payrollService->updatePayroll(request: $request, id: $id);
        $this->payrollAllowanceService->updatePayrollAllowances(request: $request, payroll: $updatePayroll);
        $this->payrollDeductionService->updatePayrollDeductions(request: $request, payroll: $updatePayroll);
        $this->payrollService->adjustPayrollAmounts(payroll: $updatePayroll);

        $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Payroll->value, date: $updatePayroll->date, accountId: $updatePayroll->expense_account_id, transId: $updatePayroll->id, amount: $updatePayroll->gross_amount, amountType: 'debit', branchId: $updatePayroll->branchId);
    }

    public function deleteMethodContainer(int $id): ?array
    {
        $deletePayroll = $this->payrollService->deletePayroll(id: $id);

        if ($deletePayroll['pass'] == false) {

            return ['pass' => false, 'msg' => $deletePayroll['msg']];
        }

        return ['pass' => true];
    }
}
