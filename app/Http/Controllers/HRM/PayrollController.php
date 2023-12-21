<?php

namespace App\Http\Controllers\HRM;

use Carbon\Carbon;
use App\Models\Hrm\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Hrm\PayrollService;
use App\Services\Setups\BranchService;
use App\Services\Hrm\DepartmentService;
use App\Enums\AllowanceAndDeductionType;
use App\Services\Accounts\AccountService;
use App\Services\Hrm\PayrollAllowanceService;
use App\Services\Hrm\PayrollDeductionService;
use App\Interfaces\CodeGenerationServiceInterface;

class PayrollController extends Controller
{
    public function __construct(
        private PayrollService $payrollService,
        private PayrollAllowanceService $payrollAllowanceService,
        private PayrollDeductionService $payrollDeductionService,
        private UserService $userService,
        private DepartmentService $departmentService,
        private BranchService $branchService,
        private AccountService $accountService,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('payrolls_index')) {

            abort(403, __('Access Forbidden.'));
        }

        if ($request->ajax()) {

            return $this->payrollService->payrollsTable(request: $request);
        }

        $departments = $this->departmentService->departments()->get(['id', 'name']);
        $users = $this->userService->users()->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name', 'emp_id']);
        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('hrm.payrolls.index', compact('users', 'departments', 'branches'));
    }

    public function show($id)
    {
        $payroll = $this->payrollService->singlePayroll(
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

        return view('hrm.payrolls.ajax_view.show', compact('payroll'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('payrolls_create')) {

            abort(403, __('Access Forbidden.'));
        }

        $month_year = explode('-', $request->month_year);
        $year = $month_year[0];
        $dateTime = \DateTime::createFromFormat('m', $month_year[1]);
        $month = $dateTime->format('F');

        $payroll = $this->payrollService->singlePayroll()->where('user_id', $request->user_id)->where('month', $month)->where('year', $year)->first();

        if ($payroll) {

            return redirect()->route('hrm.payrolls.edit', $payroll->id);
        }

        $user = $this->userService->singleUser('id', $request->user_id);
        $attendances = DB::table('hrm_attendances')->where('user_id', $request->user_id)->where('month', $month)->where('year', $year)->get();

        $totalHours = 0;
        $totalPresent = 0;
        foreach ($attendances as $attendance) {

            if ($attendance->is_completed == 1) {

                $startTime = Carbon::parse($attendance->clock_in_ts);
                $endTime = Carbon::parse($attendance->clock_out_ts);
                $totalSeconds = $startTime->diffInSeconds($endTime);
                $minutes = $totalSeconds / 60;
                $hours = $minutes / 60;
                $totalHours += $hours;
            }

            $totalPresent += 1;
        }

        $expenseAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        $allowances = DB::table('hrm_allowances')->where('type', AllowanceAndDeductionType::Allowance->value)->get();
        $deductions = DB::table('hrm_allowances')->where('type', AllowanceAndDeductionType::Deduction->value)->get();

        return view('hrm.payrolls.create', compact('user', 'expenseAccounts', 'month', 'year', 'totalHours', 'totalPresent', 'allowances', 'deductions'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerator)
    {
        if (!auth()->user()->can('payrolls_create')) {

            abort(403, __('Access Forbidden.'));
        }

        $this->payrollService->storeAndUpdateValidation(request: $request);

        try {
            DB::beginTransaction();

            $addPayroll = $this->payrollService->addPayroll(request: $request, codeGenerator: $codeGenerator);

            $this->payrollAllowanceService->addPayrollAllowances(request: $request, payroll: $addPayroll);
            $this->payrollDeductionService->addPayrollDeductions(request: $request, payroll: $addPayroll);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', __('Payroll created successfully'));
        return response()->json(__('Payroll created successfully'));
    }

    public function edit($id)
    {
        if (!auth()->user()->can('payrolls_edit')) {

            abort(403, __('Access Forbidden.'));
        }

        $payroll = $this->payrollService->singlePayroll(
            with: [
                'user',
                'allowances',
                'allowances.allowance',
                'deductions',
                'deductions.deduction',
            ]
        )->where('id', $id)->first();

        $attendances = DB::table('hrm_attendances')->where('user_id', $payroll->user_id)->where('month', $payroll->month)->where('year', $payroll->month)->get();

        $totalHours = 0;
        $totalPresent = 0;
        foreach ($attendances as $attendance) {

            if ($attendance->is_completed == 1) {

                $startTime = Carbon::parse($attendance->clock_in_ts);
                $endTime = Carbon::parse($attendance->clock_out_ts);
                $totalSeconds = $startTime->diffInSeconds($endTime);
                $minutes = $totalSeconds / 60;
                $hours = $minutes / 60;
                $totalHours += $hours;
            }

            $totalPresent += 1;
        }

        $expenseAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        return view('hrm.payrolls.edit', compact('payroll', 'expenseAccounts', 'totalHours', 'totalPresent'));
    }

    public function update($id, Request $request)
    {
        if (!auth()->user()->can('payrolls_edit')) {

            abort(403, __('Access Forbidden.'));
        }

       $this->payrollService->storeAndUpdateValidation(request: $request);

        try {
            DB::beginTransaction();

            $updatePayroll = $this->payrollService->updatePayroll(request: $request, id: $id);
            $this->payrollAllowanceService->updatePayrollAllowances(request: $request, payroll: $updatePayroll);
            $this->payrollDeductionService->updatePayrollDeductions(request: $request, payroll: $updatePayroll);
            $this->payrollService->adjustPayrollAmounts(payroll: $updatePayroll);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', __('Payroll updated successfully.'));
        return response()->json(__('Payroll updated successfully.'));
    }

    public function delete($id, Request $request,)
    {
        if (!auth()->user()->can('payrolls_delete')) {

            abort(403, __('Access Forbidden.'));
        }

        $deletePayroll = $this->payrollService->deletePayroll(id: $id);

        return response()->json(__('Payroll deleted successfully.'));
    }
}
