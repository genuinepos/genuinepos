<?php

namespace App\Http\Controllers\Accounts\Reports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Accounts\Reports\FinancialReport\AssetAmountsService;
use App\Services\Accounts\Reports\FinancialReport\ExpenseAmountsService;
use App\Services\Accounts\Reports\FinancialReport\IncomeAmountsService;
use App\Services\Accounts\Reports\FinancialReport\LiabilityAmountsService;

class FinancialReportController extends Controller
{
    public function __construct(
        private AssetAmountsService $assetAmountsService,
        private LiabilityAmountsService $liabilityAmountsService,
        private ExpenseAmountsService $expenseAmountsService,
        private IncomeAmountsService $incomeAmountsService,
        private BranchService $branchService,
    ) {
    }

    public function index()
    {
        abort_if(!auth()->user()->can('financial_report'), 403);

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        return view('accounting.reports.financial_report.index', compact('branches'));
    }

    public function financialAmounts(Request $request)
    {
        $generalSettings = config('generalSettings');
        $accountStartDate = date('Y-m-d', strtotime($generalSettings['business_or_shop__account_start_date']));

        $assetDetails = $this->assetAmountsService->details(request: $request, accountStartDate: $accountStartDate);
        $liabilityDetails = $this->liabilityAmountsService->details(request: $request, accountStartDate: $accountStartDate);
        $expenseDetails = $this->expenseAmountsService->details(request: $request, accountStartDate: $accountStartDate);
        $incomeDetails = $this->incomeAmountsService->details(request: $request, accountStartDate: $accountStartDate);

        return view('accounting.reports.financial_report.ajax_view.financial_amounts', compact('assetDetails', 'liabilityDetails', 'expenseDetails', 'incomeDetails'));
    }

    public function print(Request $request)
    {
        abort_if(!auth()->user()->can('financial_report'), 403);

        $generalSettings = config('generalSettings');
        $accountStartDate = date('Y-m-d', strtotime($generalSettings['business_or_shop__account_start_date']));

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

        $assetDetails = $this->assetAmountsService->details(request: $request, accountStartDate: $accountStartDate);
        $liabilityDetails = $this->liabilityAmountsService->details(request: $request, accountStartDate: $accountStartDate);
        $expenseDetails = $this->expenseAmountsService->details(request: $request, accountStartDate: $accountStartDate);
        $incomeDetails = $this->incomeAmountsService->details(request: $request, accountStartDate: $accountStartDate);

        return view('accounting.reports.financial_report.ajax_view.print_financial_report',  compact(
            'ownOrParentBranch',
            'filteredBranchName',
            'filteredChildBranchName',
            'fromDate',
            'toDate',
            'assetDetails',
            'liabilityDetails',
            'expenseDetails',
            'incomeDetails',
        ));
    }
}
