<?php

use App\Models\Account;
use App\Models\Accounts\AccountGroup;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

Route::get('my-test', function () {

    // return $accounts = Account::query()->with(['bank', 'bankAccessBranch'])
    //     ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
    //     ->whereIn('account_groups.sub_sub_group_number', [1, 2, 11])
    //     ->get();

    // $str = 'Shop 1';
    // $exp = explode(' ', $str);

    // $str1 = isset($exp[0]) ? str_split($exp[0])[0] : '';
    // $str2 = isset($exp[1]) ? str_split($exp[1])[0] : '';

    // return $str1.$str2;
    // $str = 'DifferentShop';
    // return $str = preg_replace("/[A-Z]/", ' ' . "$0", $str);

    $branchId = 19;
    return $accountGroups = AccountGroup::with([
        'accounts',
        'accounts.group:id,sorting_number,sub_sub_group_number',
        'accounts.bankAccessBranch'
    ])->whereIn('sub_sub_group_number', [1, 2, 11])->where('branch_id', $branchId)->orWhere('is_global', 1)->get();

    $filteredAccounts = [];
    foreach ($accountGroups as $accountGroups) {

        if (count($accountGroups->accounts) > 0) {

            foreach ($accountGroups->accounts as $account) {

                $account->sorting_number = $account->group->sorting_number;
                $account->is_bank_account = ($account->group->sub_sub_group_number == 2 || $account->group->sub_sub_group_number) == 1 ? 1 : 0;
                $account->has_bank_access_branch = 0;

                if (isset($account->bankAccessBranch)) {

                    $account->has_bank_access_branch = 1;
                }

                if (isset($account->bank_access_branch)) {

                    $account->sorting_number = $account->group->sorting_number;
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
});

Route::get('t-id', function () {
    dd(tenant());
});
