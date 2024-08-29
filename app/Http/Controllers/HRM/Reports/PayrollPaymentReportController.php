<?php

namespace App\Http\Controllers\HRM\Reports;

use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Hrm\DepartmentService;
use App\Services\Hrm\Reports\PayrollPaymentReportService;
use App\Http\Requests\HRM\Reports\PayrollPaymentReportIndexRequest;
use App\Http\Requests\HRM\Reports\PayrollPaymentReportPrintRequest;

class PayrollPaymentReportController extends Controller
{
    public function __construct(
        private PayrollPaymentReportService $payrollPaymentReportService,
        private UserService $userService,
        private DepartmentService $departmentService,
        private BranchService $branchService,
    ) {
    }

    public function index(PayrollPaymentReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->payrollPaymentReportService->payrollPaymentReportTable(request: $request);
        }

        $departments = $this->departmentService->departments()->get(['id', 'name']);
        $users = $this->userService->users()->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name', 'emp_id']);
        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('hrm.reports.payroll_payments_report.index', compact('branches', 'departments', 'users'));
    }

    public function print(PayrollPaymentReportPrintRequest $request)
    {
        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $filteredBranchName = $request->branch_name;
        $filteredDepartmentName = $request->department_name;
        $filteredUserName = $request->user_name;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $payments = $this->payrollPaymentReportService->query(request: $request)->get();

        return view('hrm.reports.payroll_payments_report.ajax_view.print', compact('payments', 'ownOrParentBranch', 'filteredBranchName', 'fromDate', 'toDate'));
    }
}
