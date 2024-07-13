<?php

namespace App\Http\Controllers\HRM\Reports;

use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Hrm\DepartmentService;
use App\Services\Hrm\Reports\PayrollReportService;
use App\Http\Requests\HRM\Reports\PayrollReportIndexRequest;
use App\Http\Requests\HRM\Reports\PayrollReportPrintRequest;

class PayrollReportController extends Controller
{
    public function __construct(
        private PayrollReportService $payrollReportService,
        private UserService $userService,
        private DepartmentService $departmentService,
        private BranchService $branchService,
    ) {
    }

    public function index(PayrollReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->payrollReportService->payrollReportTable(request: $request);
        }

        $departments = $this->departmentService->departments()->get(['id', 'name']);
        $users = $this->userService->users()->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name', 'emp_id']);
        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('hrm.reports.payroll_report.index', compact('branches', 'departments', 'users'));
    }

    public function print(PayrollReportPrintRequest $request)
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

        $month = '';
        $year = '';
        if ($request->month_year) {
            $month_year = explode('-', $request->month_year);
            $year = $month_year[0];
            $dateTime = \DateTime::createFromFormat('m', $month_year[1]);
            $month = $dateTime->format('F');
        }

        $payrolls = $this->payrollReportService->query(request: $request)->get();

        return view('hrm.reports.payroll_report.ajax_view.print', compact('payrolls', 'ownOrParentBranch', 'filteredBranchName', 'filteredDepartmentName', 'filteredUserName', 'fromDate', 'toDate', 'month', 'year'));
    }
}
