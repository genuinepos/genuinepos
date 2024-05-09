<?php

namespace App\Http\Controllers\Accounts\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\Reports\TrialBalance\AssetTrialBalanceService;

class TrialBalanceController extends Controller
{
    public function __construct(
        private AssetTrialBalanceService $assetTrialBalanceService,
        private BranchService $branchService,
    ) {
    }

    public function index()
    {
        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        return view('accounting.reports.trial_balance.index', compact('branches'));
    }

    public function trialBalanceData(Request $request)
    {
        $generalSettings = config('generalSettings');
        $accountStartDate = date('Y-m-d', strtotime($generalSettings['business_or_shop__account_start_date']));
        return $this->assetTrialBalanceService->details(request: $request, accountStartDate: $accountStartDate);
    }
}
