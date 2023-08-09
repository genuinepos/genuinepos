<?php

namespace App\Services\Accounts;

use App\Models\Accounts\BankAccessBranch;

class BankAccessBranchService
{
    public function addBankAccessBranch(int $bankAccountId, array $branchIds = []): void
    {
        foreach ($branchIds as $branchId) {

            $addBankAccessBranch = new BankAccessBranch();
            $addBankAccessBranch->bank_account_id = $bankAccountId;
            $addBankAccessBranch->branch_id = $branchId;
            $addBankAccessBranch->save();
        }
    }

    public function updateBankAccessBranch(object $bankAccount, array $branchIds = []): void
    {
        foreach ($bankAccount->bankAccessBranches as $bankAccessBranch) {

            $bankAccessBranch->is_delete_in_update = 1;
            $bankAccessBranch->save();
        }

        if (isset($branchIds) && count($branchIds) > 0) {

            foreach ($branchIds as $branchId) {

                $bankAccessBranch = BankAccessBranch::where('bank_account_id', $bankAccount->id)->where('branch_id', $branchId)->first();

                $addOrEditBankAccessBranch = '';
                if ($bankAccessBranch) {

                    $addOrEditBankAccessBranch = $bankAccessBranch;
                } else {

                    $addOrEditBankAccessBranch =  new BankAccessBranch();
                }

                $addOrEditBankAccessBranch->bank_account_id = $bankAccount->id;
                $addOrEditBankAccessBranch->branch_id = $branchId;
                $addOrEditBankAccessBranch->is_delete_in_update = 0;
                $addOrEditBankAccessBranch->save();
            }
        }

        $deletableUnusedBankAccessBranches = BankAccessBranch::where('bank_account_id', $bankAccount->id)->where('is_delete_in_update', 1)->get();

        foreach ($deletableUnusedBankAccessBranches as $deletableUnusedBankAccessBranch) {

            $deletableUnusedBankAccessBranch->delete();
        }
    }
}
