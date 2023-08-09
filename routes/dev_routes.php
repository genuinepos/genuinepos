<?php

use App\Models\Account;
use Illuminate\Support\Facades\Route;

Route::get('my-test', function () {

    return $accounts = Account::query()->with(['bank', 'bankAccessBranch'])
        ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
        ->whereIn('account_groups.sub_sub_group_number', [1, 2, 11])
        ->get();
});
