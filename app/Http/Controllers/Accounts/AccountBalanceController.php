<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountBalanceService;
use Illuminate\Http\Request;

class AccountBalanceController extends Controller
{
    public function __construct(
        private AccountBalanceService $accountBalanceService,
    ) {
    }

    public function accountBalance(Request $request, $accountId)
    {
        return $this->accountBalanceService->accountBalance(accountId: $accountId, fromDate: $request->from_date, toDate: $request->to_date, branchId: $request->branch_id);
    }
}
