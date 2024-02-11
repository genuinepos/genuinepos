<?php

namespace App\Http\Controllers\Accounts\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\Reports\ProfitLossService;

class ProfitLossReportController extends Controller
{
    public function __construct(private BranchService $branchService, private ProfitLossService $profitLossService)
    {
        $this->middleware('expireDate');
    }

    public function index()
    {
        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        return view('accounting.reports.profit_loss_report.index', compact('branches'));
    }

    public function profitLossAmounts(Request $request)
    {
        $profitLossAmounts = $this->profitLossService->profitLossAmounts(branchId: $request->branch_id, childBranchId: $request->child_branch_id, fromDate: $request->from_date, toDate: $request->to_date);

        return view('accounting.reports.profit_loss_report.ajax_view.profit_loss', compact('profitLossAmounts'));
    }

    public function printProfitLoss(Request $request)
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
        $filteredChildBranchName = $request->child_branch_name;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $profitLossAmounts = $this->profitLossService->profitLossAmounts(branchId: $request->branch_id, childBranchId: $request->child_branch_id, fromDate: $request->from_date, toDate: $request->to_date);

        return view('accounting.reports.profit_loss_report.ajax_view.print_profit_loss', compact(
            'ownOrParentBranch',
            'filteredBranchName',
            'filteredChildBranchName',
            'fromDate',
            'toDate',
            'profitLossAmounts',
        ));
    }
}
