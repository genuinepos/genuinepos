<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountGroupService;
use App\Services\Accounts\DutiesAndTaxesAccountService;
use Illuminate\Http\Request;

class DutyAndTaxAccountController extends Controller
{
    public function __construct(
        private DutiesAndTaxesAccountService $dutiesAndTaxesAccountService,
        private AccountGroupService $accountGroupService,
    ) {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('duties_and_taxes_index'), 403);

        if ($request->ajax()) {

            return $this->dutiesAndTaxesAccountService->dutiesAndTaxesAccountListTable($request);
        }

        $accountGroups = $this->accountGroupService->singleAccountGroupByAnyCondition(with: ['parentGroup'])
            ->where('main_group_number', 2)
            ->where('sub_group_number', 7)
            ->where('sub_sub_group_number', 8)
            ->get();

        return view('accounting.accounts.duties_and_taxes.index', compact('accountGroups'));
    }
}
