<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\InvoiceLayoutService;

class BranchSettingController extends Controller
{
    public function __construct(
        private BranchService $branchService,
        private BranchSettingService $branchSettingService,
        private InvoiceLayoutService $invoiceLayoutService,
    ) {
    }

    public function edit($branchId)
    {
        if (!auth()->user()->can('branch')) {

            abort(403, 'Access Forbidden.');
        }

        $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: $branchId, with: ['branch', 'branch.parentBranch']);
        $invoiceLayouts = $this->invoiceLayoutService->invoiceLayouts(branchId: $branchId);

        $taxAccounts = DB::table('accounts')->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('accounts.branch_id', $branchId)
            ->where('account_groups.is_default_tax_calculator', 1)
            ->select('accounts.id', 'accounts.name')
            ->get();

        return view('setups.branches.settings.edit', compact('branchSetting', 'invoiceLayouts', 'taxAccounts'));
    }

    public function update(Request $request, $branchId)
    {
        if (!auth()->user()->can('branch')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $this->branchSettingService->updateBranchSettings(branchId: $branchId, request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Shop settings updated successfully"));
    }
}
