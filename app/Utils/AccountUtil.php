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

        $expenseLoan = DB::table('cash_flows')
            ->where('cash_flows.account_id', $account_id)
            ->where('loan_id', '!=', NULL)
            ->where('debit', '!=', NULL)
            ->leftJoin('loans', 'cash_flows.loan_id', 'loans.id')
            ->where('loans.loan_by', 'Expense')->select(DB::raw('sum(debit) as t_debit'))
            ->groupBy('loans.loan_by')
            ->get();

        $acDebit = $cashFlowD->sum('t_debit') - $expenseLoan->sum('t_debit');

        $cashFlowC = DB::table('cash_flows')->where('cash_type', 2)
            ->where('credit', '!=', NULL)
            ->where('cash_flows.account_id', $account_id)
            ->select(DB::raw('sum(credit) as t_credit'))
            ->get();

        $expenseLoan = DB::table('loans')->where('loan_by', 'Expense')->select(DB::raw('sum(loan_amount) as amt'))->get();

        $account = Account::where('id', $account_id)->first();
        $account->debit = $acDebit;
        $account->credit = $cashFlowC;
        $account->balance = $cashFlowC->sum('t_credit') - $acDebit;
        $account->save();
        return $account->balance;
    }
}