<?php

namespace App\Services\Accounts;

use App\Models\Accounts\BankAccessBranch;

class BankAccessBranchService
{
    public function addBankAccessBranch(int $bankAccountId, array $branchIds = [])
    {

        foreach ($branchIds as $branchId) {

            $addBankAccessBranch = new BankAccessBranch();
            $addBankAccessBranch->bank_account_id = $bankAccountId;
            $addBankAccessBranch->branch_id = $branchId;
            $addBankAccessBranch->save();
        }
    }
}
