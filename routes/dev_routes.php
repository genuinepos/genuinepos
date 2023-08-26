<?php

use App\Models\Account;
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


    $query = DB::table('branches')->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

    return $query->select(
        'branches.id',
        'branches.branch_type',
        'branches.name as branch_name',
        'branches.branch_code',
        'branches.phone',
        'branches.logo',
        'branches.city',
        'branches.state',
        'branches.zip_code',
        'branches.country',
        'parentBranch.name as parent_branch_name',
    )->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();
});

Route::get('t-id', function () {
    dd(tenant());
});
