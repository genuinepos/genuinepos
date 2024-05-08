<?php

namespace App\Services\Accounts;

use App\Models\Accounts\AccountOpeningBalance;

class AccountOpeningBalanceService
{
    public function addOrUpdateAccountOpeningBalance(?int $branchId, int $accountId, string $openingBalanceType, float $openingBalance): void
    {
        $addOrUpdateAccountOpeningBalance = null;
        $accountOpeningBalance = $this->accountOpeningBalance(branchId: $branchId, accountId: $accountId);
        if (isset($accountOpeningBalance)) {

            $addOrUpdateAccountOpeningBalance = $accountOpeningBalance;
        }else {

            $addOrUpdateAccountOpeningBalance = new AccountOpeningBalance();
        }

        $addOrUpdateAccountOpeningBalance->branch_id = $branchId;
        $addOrUpdateAccountOpeningBalance->account_id = $accountId;
        $addOrUpdateAccountOpeningBalance->opening_balance_type = $openingBalanceType;
        $addOrUpdateAccountOpeningBalance->opening_balance = $openingBalance;
        $addOrUpdateAccountOpeningBalance->save();
    }

    public function accountOpeningBalance(?int $branchId, int $accountId): ?object
    {
        $query = AccountOpeningBalance::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('branch_id', $branchId)->where('id', $accountId)->first();
    }
}
