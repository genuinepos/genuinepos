<?php

namespace App\Services\Accounts;

use Illuminate\Support\Facades\DB;

class AccountFilterService
{
    public function filterCashBankAccounts(object $accountGroups) : ?array
    {
        $filteredAccounts = [];
        foreach ($accountGroups as $accountGroups) {

            if (count($accountGroups->accounts) > 0) {

                foreach ($accountGroups->accounts as $account) {

                    $account->sorting_number = $account->group->sorting_number;
                    $account->is_bank_account = ($account->group->sub_sub_group_number == 1 || $account->group->sub_sub_group_number == 11) == 1 ? 1 : 0;
                    $account->has_bank_access_branch = 0;

                    if (isset($account->bankAccessBranch)) {

                        $account->has_bank_access_branch = 1;
                    }

                    if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) {

                        $account->has_bank_access_branch = 1;
                    }

                    unset($account->group);
                    unset($account->bankAccessBranch);
                    array_push($filteredAccounts, $account);
                }
            }
        }

        usort($filteredAccounts, function ($item) {

            return $item['sorting_number'];
        });

        return $filteredAccounts;
    }
}
