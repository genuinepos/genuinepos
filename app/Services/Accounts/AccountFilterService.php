<?php

namespace App\Services\Accounts;

class AccountFilterService
{
    public function filterCashBankAccounts(object $accounts)
    {
        $filteredAccounts = [];

        if (count($accounts) > 0) {

            foreach ($accounts as $account) {

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

        return collect($filteredAccounts)->values()->sortBy('sorting_number');
    }
}
