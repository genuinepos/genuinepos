<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountBalanceService;

class AccountBalanceController extends Controller
{
    public function __construct(
        private AccountBalanceService $accountBalanceService,
    ) {
    }

    public function accountBalance(Request $request, $accountId)
    {
        return $this->accountBalanceService->accountBalance(request: $request, accountId: $accountId);
    }
}
