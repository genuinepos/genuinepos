<?php
namespace App\Utils;

use App\Models\LoanCompany;
use Illuminate\Support\Facades\DB;

class LoanUtil
{
    public function adjustCompanyPayLoanAmount($companyId)
    {
        $payLoan = DB::table('loans')->where('loan_company_id', $companyId)
        ->where('loans.type', 1)
        ->select(
            DB::raw('sum(loan_amount) as t_amount'),
            DB::raw('sum(due) as t_due'),
            DB::raw('sum(total_paid) as t_paid'),
        )->groupBy('loans.loan_company_id')->get();

        $company = LoanCompany::where('id', $companyId)->first();
        $company->pay_loan_amount = $payLoan->sum('t_amount');
        $company->pay_loan_due = $payLoan->sum('t_due');
        $company->total_receive = $payLoan->sum('t_paid');
        $company->save();
    }

    public function adjustCompanyReceiveLoanAmount($companyId)
    {
        $receiveLoan = DB::table('loans')->where('loan_company_id', $companyId)
        ->where('loans.type', 2)
        ->select(
            DB::raw('sum(loan_amount) as t_amount'),
            DB::raw('sum(due) as t_due'),
            DB::raw('sum(total_paid) as t_paid'),
        )->groupBy('loans.loan_company_id')->get();

        $company = LoanCompany::where('id', $companyId)->first();
        $company->get_loan_amount = $receiveLoan->sum('t_amount');
        $company->get_loan_due = $receiveLoan->sum('t_due');
        $company->total_pay = $receiveLoan->sum('t_paid');
        $company->save();
    }
}