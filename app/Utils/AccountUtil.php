<?php

namespace App\Utils;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountUtil
{
    public function adjustAccountBalance($account_id)
    {
        $cashFlowD = DB::table('cash_flows')->where('cash_type', 1)
            ->where('account_id', $account_id)
            ->where('debit', '!=', NULL)
            ->select(DB::raw('sum(debit) as t_debit'))
            ->get();
        
        $totalDebit = $cashFlowD->sum('t_debit') ? $cashFlowD->sum('t_debit') : 0;

        $expenseLoan = DB::table('cash_flows')
            ->where('cash_flows.account_id', $account_id)
            ->where('loan_id', '!=', NULL)
            ->where('debit', '!=', NULL)
            ->leftJoin('loans', 'cash_flows.loan_id', 'loans.id')
            ->where('loans.loan_by', 'Expense')->select(DB::raw('sum(debit) as t_debit'))
            ->groupBy('loans.loan_by')
            ->get();
        
        $totalExpenseLoan = $expenseLoan->sum('t_debit') ? $expenseLoan->sum('t_debit') : 0;

        $acDebit = $totalDebit - $totalExpenseLoan;

        $cashFlowC = DB::table('cash_flows')->where('cash_type', 2)
            ->where('credit', '!=', NULL)
            ->where('cash_flows.account_id', $account_id)
            ->select(DB::raw('sum(credit) as t_credit'))
            ->get();
        
        $totalCredit = $cashFlowC->sum('t_credit') ? $cashFlowC->sum('t_credit') : 0;

        $account = Account::where('id', $account_id)->first();
        $account->debit = $acDebit;
        $account->credit = $totalCredit;
        $account->balance = $totalCredit - $acDebit;
        $account->save();
        return $account->balance;
    }
}