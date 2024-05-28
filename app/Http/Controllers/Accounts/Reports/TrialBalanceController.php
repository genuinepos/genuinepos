<?php

namespace App\Http\Controllers\Accounts\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\Reports\TrialBalance\GroupWiseTrialBalanceService;

class TrialBalanceController extends Controller
{
    public function __construct(
        private GroupWiseTrialBalanceService $groupWiseTrialBalanceService,
        private BranchService $branchService,
    ) {
    }

    public function index()
    {
        abort_if(!auth()->user()->can('trial_balance'), 403);

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        return view('accounting.reports.trial_balance.index', compact('branches'));
    }

    public function trialBalanceData(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        if ($request->from_date && !isset($request->to_date)) {

            return response()->json(['errorMsg' => __('To Date is required')]);
        } elseif ($request->to_date && !isset($request->from_date)) {

            return response()->json(['errorMsg' => __('From Date is required')]);
        }

        $formatOfReport = $request->format_of_report;
        $generalSettings = config('generalSettings');
        $accountStartDate = date('Y-m-d', strtotime($generalSettings['business_or_shop__account_start_date']));
         $accountGroups = $this->groupWiseTrialBalanceService->accountGroups(request: $request, accountStartDate: $accountStartDate);

        return view('accounting.reports.trial_balance.ajax_view.trial_balance_group_wise_data', compact(
            'accountGroups',
            'formatOfReport',
            'fromDate',
            'toDate',
        ));
    }

    public function trialBalancePrint(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        if ($request->from_date && !isset($request->to_date)) {

            return response()->json(['errorMsg' => __('To Date is required')]);
        } elseif ($request->to_date && !isset($request->from_date)) {

            return response()->json(['errorMsg' => __('From Date is required')]);
        }

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

        $formatOfReport = $request->format_of_report;
        $generalSettings = config('generalSettings');
        $accountStartDate = date('Y-m-d', strtotime($generalSettings['business_or_shop__account_start_date']));
        $accountGroups = $this->groupWiseTrialBalanceService->accountGroups(request: $request, accountStartDate: $accountStartDate);

        return view('accounting.reports.trial_balance.ajax_view.print_trial_balance_group_wise_data',
            compact(
                'accountGroups',
                'formatOfReport',
                'ownOrParentBranch',
                'filteredBranchName',
                'filteredChildBranchName',
                'fromDate',
                'toDate',
            )
        );
    }
}
