<?php

namespace App\Http\Controllers\Accounts\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountGroupService;
use App\Services\Accounts\Reports\ExpenseReport\ExpenseReportService;

class ExpenseReportController extends Controller
{
    public function __construct(
        private ExpenseReportService $expenseReportService,
        private AccountGroupService $accountGroupService,
        private BranchService $branchService
    ) {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('expense_report'), 403);

        if ($request->ajax()) {

            return $this->expenseReportService->expenseReportTable(request: $request);
        }

        $expenseGroups = $this->accountGroupService->accountGroups()
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('id', 'name')->get();

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('accounting.reports.expense_report.index', compact('expenseGroups', 'branches'));
    }

    public function print(Request $request)
    {
        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $expenses = $this->expenseReportService->query(request: $request)->get();

        $filteredBranchName = $request->branch_name;
        $filteredChildBranchName = $request->child_branch_name;
        $filteredExpenseGroupName = $request->expense_group_name;
        $filteredExpenseAccountName = $request->expense_account_name;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $count = count($expenses);
        $veryLastDate = $count > 0 ? $expenses->last()->date : '';
        $lastRow = $count - 1;

        return view('accounting.reports.expense_report.ajax_view.print', compact(
            'expenses',
            'ownOrParentBranch',
            'filteredBranchName',
            'filteredChildBranchName',
            'filteredExpenseGroupName',
            'filteredExpenseAccountName',
            'fromDate',
            'toDate',
            'count',
            'veryLastDate',
            'lastRow',
        ));
    }
}
