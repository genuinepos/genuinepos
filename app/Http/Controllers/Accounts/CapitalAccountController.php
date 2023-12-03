<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountGroupService;
use App\Services\Accounts\CapitalAccountService;
use Illuminate\Http\Request;

class CapitalAccountController extends Controller
{
    public function __construct(
        private CapitalAccountService $capitalAccountService,
        private AccountGroupService $accountGroupService,
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('capital_accounts_index')) {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->capitalAccountService->capitalAccountListTable($request);
        }

        $accountGroups = $this->accountGroupService->singleAccountGroupByAnyCondition(with: ['parentGroup'])
            ->where('main_group_number', 2)
            ->where('sub_group_number', 6)
            ->get();

        return view('accounting.accounts.capital_accounts.index', compact('accountGroups'));
    }
}
