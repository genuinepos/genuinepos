<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Hrm\PayrollService;
use App\Services\Setups\BranchService;
use App\Services\Hrm\DepartmentService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Hrm\PayrollAllowanceService;
use App\Services\Hrm\PayrollDeductionService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Interfaces\Hrm\PayrollControllerMethodContainersInterface;

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
        private DayBookService $dayBookService,
    ) {
        $this->middleware('subscriptionRestrictions');
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

    public function show($id, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        $showMethodContainer = $payrollControllerMethodContainersInterface->showMethodContainer(id: $id, payrollService: $this->payrollService);

        extract($showMethodContainer);

        return view('hrm.payrolls.ajax_view.show', compact('payroll'));
    }

    public function print($id, Request $request, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        $printMethodContainer = $payrollControllerMethodContainersInterface->printMethodContainer(id: $id, request: $request, payrollService: $this->payrollService);

        extract($printMethodContainer);

        return view('hrm.print_templates.print_payroll', compact('payroll', 'printPageSize'));
    }

    public function create(Request $request, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        if (!auth()->user()->can('payrolls_create')) {

            abort(403, __('Access Forbidden.'));
        }

        $createMethodContainer = $payrollControllerMethodContainersInterface->createMethodContainer(
            request: $request,
            payrollService: $this->payrollService,
            accountService: $this->accountService,
            userService: $this->userService,
        );

        extract($createMethodContainer);

        if (isset($payroll)) {

            return redirect()->route('hrm.payrolls.edit', $payroll->id);
        }

        return view('hrm.payrolls.create', compact('user', 'expenseAccounts', 'month', 'year', 'totalHours', 'totalPresent', 'allowances', 'deductions'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerator, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        if (!auth()->user()->can('payrolls_create')) {

            abort(403, __('Access Forbidden.'));
        }

        $this->payrollService->storeAndUpdateValidation(request: $request);

        try {
            DB::beginTransaction();

            $payrollControllerMethodContainersInterface->storeMethodContainer(
                request: $request,
                payrollService: $this->payrollService,
                payrollAllowanceService: $this->payrollAllowanceService,
                payrollDeductionService: $this->payrollDeductionService,
                dayBookService: $this->dayBookService,
                codeGenerator: $codeGenerator,
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', __('Payroll created successfully'));
        return response()->json(__('Payroll created successfully'));
    }

    public function edit($id, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        if (!auth()->user()->can('payrolls_edit')) {

            abort(403, __('Access Forbidden.'));
        }

        $editMethodContainer = $payrollControllerMethodContainersInterface->editMethodContainer(
            id: $id,
            payrollService: $this->payrollService,
            accountService: $this->accountService,
        );

        extract($editMethodContainer);

        return view('hrm.payrolls.edit', compact('payroll', 'expenseAccounts', 'totalHours', 'totalPresent'));
    }

    public function update($id, Request $request, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        if (!auth()->user()->can('payrolls_edit')) {

            abort(403, __('Access Forbidden.'));
        }

        $this->payrollService->storeAndUpdateValidation(request: $request);

        try {
            DB::beginTransaction();

            $payrollControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                payrollService: $this->payrollService,
                payrollAllowanceService: $this->payrollAllowanceService,
                payrollDeductionService: $this->payrollDeductionService,
                dayBookService: $this->dayBookService,
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', __('Payroll updated successfully.'));
        return response()->json(__('Payroll updated successfully.'));
    }

    public function delete($id, Request $request, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        if (!auth()->user()->can('payrolls_delete')) {

            abort(403, __('Access Forbidden.'));
        }

        $deleteMethodContainer = $payrollControllerMethodContainersInterface->deleteMethodContainer(id: $id, payrollService: $this->payrollService);

        if ($deleteMethodContainer['pass'] == false) {

            return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
        }

        return response()->json(__('Payroll deleted successfully.'));
    }
}
