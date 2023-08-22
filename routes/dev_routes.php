<?php

use App\Models\Account;
use Illuminate\Support\Facades\Route;

Route::get('my-test', function () {

    // return $accounts = Account::query()->with(['bank', 'bankAccessBranch'])
    //     ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
    //     ->whereIn('account_groups.sub_sub_group_number', [1, 2, 11])
    //     ->get();

    $str = 'Shop 1';
    $exp = explode(' ', $str);

    $str1 = isset($exp[0]) ? str_split($exp[0])[0] : '';
    $str2 = isset($exp[1]) ? str_split($exp[1])[0] : '';

    return $str1.$str2;
});

Route::get('t-id', function () {
    dd(tenant());
});
