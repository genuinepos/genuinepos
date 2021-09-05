<?php

namespace App\Utils;

use App\Models\Loan;
use App\Models\Account;
use App\Models\CashFlow;
use App\Models\LoanCompany;

class ExpenseUtil
{
    public function addLoanByExpense($request, $expenseId)
    {
        // generate reference no
        $i = 4;
        $a = 0;
        $refId = '';
        while ($a < $i) {
            $refId .= rand(1, 9);
            $a++;
        }

        $addLoan = new Loan();
        $addLoan->reference_no = 'LP'. date('Y') . $refId;
        $addLoan->branch_id = auth()->user()->branch_id;
        $addLoan->expense_id = $expenseId;
        $addLoan->loan_company_id = $request->company_id;
        $addLoan->type = 1;
        $addLoan->loan_amount = $request->loan_amount;
        $addLoan->due = $request->loan_amount;
        $addLoan->account_id = $request->account_id;
        $addLoan->loan_reason = $request->loan_reason;
        $addLoan->loan_by = 'Expense';
        $addLoan->created_user_id = auth()->id();
        $addLoan->report_date = date('Y-m-d');
        $addLoan->save();

        $addCompanyLoanAmount = LoanCompany::where('id', $request->company_id)->first();
        $addCompanyLoanAmount->pay_loan_amount += (float)$request->loan_amount;
        $addCompanyLoanAmount->pay_loan_due += (float)$request->loan_amount;
        $addCompanyLoanAmount->save();

        $account = Account::where('id', $request->account_id)->first();
        if ($account) {
            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->debit = $request->loan_amount;
            $addCashFlow->cash_type = 1;
            $addCashFlow->balance = $account->balance;
            $addCashFlow->loan_id = $addLoan->id;
            $addCashFlow->transaction_type = 10;
            $addCashFlow->date = date('d-m-Y', strtotime($request->date));
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->id();
            $addCashFlow->save();
        }
    }
}
