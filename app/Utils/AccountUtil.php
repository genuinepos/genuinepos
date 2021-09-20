<?php

namespace App\Utils;

class AccountUtil
{
    public function adjustAccountAmounts()
    {
        $totalDebitAmount = DB::table('cash_flows')->where('cash_type', 1);
    }
}