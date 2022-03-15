<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class FinancialAmountsUtil
{
    public function allFinancialAmounts($request = NULL) : array
    {
        $cashInHandBalance = $this->cashInHandBalance($request);
    }

    // public function cashInHandBalance($request)
    // {
    //     $cashInHandAmounts = DB::table('account_branches')
    //                         ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
    //                         ->where('accounts.account_type', 1)
    // }
}