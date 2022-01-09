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
        $account->balance = $acDebit - $totalCredit;
        $account->save();
        return $account->balance;
    }

    public function adjustDebitBalanceAccount($balanceType, $account_id)
    {
        $ac_ledger = DB::table('account_ledgers')
            ->where('account_ledgers.account_id', $account_id)
            ->select(
                DB::raw('sum(debit) as t_debit'),
                DB::raw('sum(credit) as t_credit')
            )
            ->groupBy('account_ledgers.account_id')
            ->get();

        $account = Account::where('id', $account_id)->first();
        $account->debit = $ac_ledger->sum('t_debit');
        $account->credit = $ac_ledger->sum('t_credit');
        $account->balance = $ac_ledger->sum('t_debit') - $ac_ledger->sum('t_credit');
        $account->save();
        return $account->balance;
    }

    public static function creatableDefaultAccount()
    {
        return  [
            1 => 'Cash',
            2 => 'Bank',
            3 => 'Purchase',
            4 => 'Purchase Return',
            5 => 'Sales',
            6 => 'Sales Return',
            7 => 'Direct Expense',
            8 => 'Indirect Expense',
            9 => 'Office Building/Factory',
            9 => 'Land',
            10 => 'Damage Stock',
            13 => 'Loans',
            15 => 'Furniture',
            15 => 'Vehicle',
            21 => 'Payroll',
            22 => 'Stock Adjustment',
            23 => 'Production',
        ];
    }

    public static function voucherTypes()
    {
        return [
            1 => 'Sales',
            2 => 'Sale Return',
            3 => 'Purchase',
            4 => 'Purchase Return',
            5 => 'Expense',
            6 => 'Production',
            7 => 'Stock Adjustment',
            8 => 'Stock Adjustment RCV AMT',
            9 => 'Expense Payment',
            10 => 'Receive Payment',
            11 => 'Paid To Supplier',
            12 => 'Return Payment',
            13 => 'Loan',
            14 => 'Loan Ins. Payment',
            15 => 'Loan Ins. Receive',
        ];
    }

    public static function voucherType()
    {
        return [
            1 => ['name' => 'Sales', 'voucher_no' => 'sale_inv_id'],
            2 => ['name' => 'Sale Return', 'voucher_no' => 'sale_return_inv'],
            3 => ['name' => 'Purchase', 'voucher_no' => 'purchase_inv_id'],
            4 => 'Purchase Return',
            5 => 'Expense',
            6 => 'Production',
            7 => 'Stock Adjustment',
            8 => 'Stock Adjustment RCV AMT',
            9 => 'Expense Payment',
            10 => 'Receive Payment',
            11 => 'Paid To Supplier',
            12 => 'Return Payment',
            13 => 'Loan',
            14 => 'Loan Ins. Payment',
            15 => 'Loan Ins. Receive',
        ];
    }
}
