<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanPaymentController extends Controller
{
    public function dueReceiveModal($company_id)
    {
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number', 'balance')->get();
        $company = DB::table('loan_companies')->where('id', $company_id)->first();
        return view('accounting.loans.ajax_view.loan_due_receive_modal', compact('accounts', 'company'));
    }

    public function dueReceiveStore(Request $request, $company_id)
    {
        $loanPayment = new LoanPayment();
        $loanPayment->company_id = $company_id;
        $loanPayment->branch_id = auth()->user()->branch_id;
        $loanPayment->user_id = auth()->user()->id;
        $loanPayment->paid_amount = $request->amount;
        $loanPayment->pay_mode = $request->pay_mode;
        $loanPayment->save();

        $duePayLoans = Loan::where('type', 1)->where('due', '>', 0)->get();
    }
}
