<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Utils\LoanUtil;
use App\Models\CashFlow;
use App\Utils\AccountUtil;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LoanPaymentDistribution;
use App\Utils\InvoiceVoucherRefIdUtil;

class LoanPaymentController extends Controller
{
    protected $accountUtil;
    protected $loanUtil;
    protected $invoiceVoucherRefIdUtil;
    public function __construct(
        AccountUtil $accountUtil,
        LoanUtil $loanUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil
    ) {
        $this->accountUtil = $accountUtil;
        $this->loanUtil = $loanUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function dueReceiveModal($company_id)
    {
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number', 'balance')->get();
        $company = DB::table('loan_companies')->where('id', $company_id)->first();
        return view('accounting.loans.ajax_view.loan_due_receive_modal', compact('accounts', 'company'));
    }

    public function dueReceiveStore(Request $request, $company_id)
    {
        $loanPayment = new LoanPayment();
        $loanPayment->voucher_no = 'PLDR'.date('my').$this->invoiceVoucherRefIdUtil->getLastId('loan_payments');
        $loanPayment->company_id = $company_id;
        $loanPayment->payment_type = 1;
        $loanPayment->branch_id = auth()->user()->branch_id;
        $loanPayment->account_id = $request->account_id;
        $loanPayment->user_id = auth()->user()->id;
        $loanPayment->paid_amount = $request->amount;
        $loanPayment->pay_mode = $request->pay_mode;
        $loanPayment->date = $request->date;
        $loanPayment->report_date = date('Y-m-d', strtotime($request->date));
        $loanPayment->save();

        if ($request->account_id) {
            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->credit = $request->amount;
            $addCashFlow->cash_type = 2;
            $addCashFlow->loan_payment_id = $loanPayment->id;
            $addCashFlow->transaction_type = 11;
            $addCashFlow->date = $request->date;
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->id();
            $addCashFlow->save();
            $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
            $addCashFlow->save();
        }

        $dueLoans = Loan::where('type', 1)->where('loan_company_id', $company_id)->where('due', '>', 0)->orderBy('id', 'asc')->get();
        $paying_amount = $request->amount;
        foreach ($dueLoans as $dueLoan) {
            if ($dueLoan->due > $paying_amount) {
                if ($paying_amount > 0) {
                    $this->addLoanPaymentDistribution($loanPayment->id, $dueLoan->id, $paying_amount, 1);
                    $paying_amount -= $paying_amount;
                    $this->loanUtil->loanAmountAdjustment($dueLoan); 
                }else {
                    break;
                }
            } elseif ($dueLoan->due == $request->amount) {
                if ($paying_amount > 0) {
                    $this->addLoanPaymentDistribution($loanPayment->id, $dueLoan->id, $paying_amount, 1);
                    $paying_amount -= $paying_amount;
                    $this->loanUtil->loanAmountAdjustment($dueLoan);
                } else {
                    break;
                }
            } elseif ($dueLoan->due < $paying_amount) {
                if ($paying_amount > 0) {
                    $this->addLoanPaymentDistribution($loanPayment->id, $dueLoan->id, $dueLoan->due, 1);
                    $paying_amount -= $dueLoan->due;
                    $this->loanUtil->loanAmountAdjustment($dueLoan);
                } else {
                    break;
                }
            }
        }

        $this->loanUtil->adjustCompanyPayLoanAmount($company_id);
        return response()->json('Pay Loan due received Successfully');
    }

    public function duePayModal($company_id)
    {
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number', 'balance')->get();
        $company = DB::table('loan_companies')->where('id', $company_id)->first();
        return view('accounting.loans.ajax_view.loan_due_pay_modal', compact('accounts', 'company'));
    }

    public function duePayStore(Request $request, $company_id)
    {
        $loanPayment = new LoanPayment();
        $loanPayment->voucher_no = 'GLDR'.date('my').$this->invoiceVoucherRefIdUtil->getLastId('loan_payments');
        $loanPayment->company_id = $company_id;
        $loanPayment->payment_type = 2;
        $loanPayment->branch_id = auth()->user()->branch_id;
        $loanPayment->account_id = $request->account_id;
        $loanPayment->user_id = auth()->user()->id;
        $loanPayment->paid_amount = $request->amount;
        $loanPayment->pay_mode = $request->pay_mode;
        $loanPayment->date = $request->date;
        $loanPayment->report_date = date('Y-m-d', strtotime($request->date));
        $loanPayment->save();

        if ($request->account_id) {
            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->debit = $request->amount;
            $addCashFlow->cash_type = 1;
            $addCashFlow->loan_payment_id = $loanPayment->id;
            $addCashFlow->transaction_type = 11;
            $addCashFlow->date = $request->date;
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->id();
            $addCashFlow->save();
            $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
            $addCashFlow->save();
        }

        $dueLoans = Loan::where('type', 2)->where('loan_company_id', $company_id)->where('due', '>', 0)->get();
        $paying_amount = $request->amount;
        foreach ($dueLoans as $dueLoan) {
            if ($dueLoan->due > $paying_amount) {
                if ($request->amount > 0) {
                    $this->addLoanPaymentDistribution($loanPayment->id, $dueLoan->id, $paying_amount, 2);
                    $this->loanUtil->loanAmountAdjustment($dueLoan);
                    $paying_amount -= $paying_amount;
                }  else {
                    break;
                }
            } elseif ($dueLoan->due == $paying_amount) {
                if ($paying_amount > 0) {
                    $this->addLoanPaymentDistribution($loanPayment->id, $dueLoan->id, $paying_amount, 2);
                    $this->loanUtil->loanAmountAdjustment($dueLoan);
                    $paying_amount -= $paying_amount;
                }  else {
                    break;
                }
            } elseif ($dueLoan->due < $paying_amount) {
                if ($paying_amount > 0) {
                    $this->addLoanPaymentDistribution($loanPayment->id, $dueLoan->id, $dueLoan->due, 2);
                    $this->loanUtil->loanAmountAdjustment($dueLoan);
                    $paying_amount -= $dueLoan->due;
                } else {
                    break;
                }
            }
        }

        $this->loanUtil->adjustCompanyReceiveLoanAmount($company_id);
        return response()->json('Get Loan due paid Successfully');
    }

    public function paymentList($company_id)
    {
        $company = DB::table('loan_companies')->where('id', $company_id)->first();
        $loan_payments = DB::table('loan_payments')
        ->leftJoin('accounts', 'loan_payments.account_id', 'accounts.id')
        ->select('loan_payments.*', 'accounts.name as ac_name', 'accounts.account_number as ac_no')
        ->where('loan_payments.company_id', $company_id)
        ->orderBy('loan_payments.report_date', 'desc')->get();
        return view('accounting.loans.ajax_view.payment_list', compact('company', 'loan_payments'));
    }

    public function delete($payment_id)
    {
        $deleteLoanPayment = LoanPayment::with(['loan_payment_distributions', 'loan_payment_distributions.loan'])->where('id', $payment_id)->first();
        $storedPaymentType = $deleteLoanPayment->payment_type;
        $storedCompanyId = $deleteLoanPayment->company_id;
        $storedAccountId = $deleteLoanPayment->account_id;
        $storedPaymentDistributions = $deleteLoanPayment->loan_payment_distributions;
        $deleteLoanPayment->delete();

        foreach ($storedPaymentDistributions as $storedPaymentDistribution) {
            $this->loanUtil->loanAmountAdjustment($storedPaymentDistribution->loan);
        }

        if ($storedPaymentType == 1) {
            $this->loanUtil->adjustCompanyPayLoanAmount($storedCompanyId);
        } else {
            $this->loanUtil->adjustCompanyReceiveLoanAmount($storedCompanyId);
        }

        if ($storedAccountId) {
            $this->accountUtil->adjustAccountBalance($storedAccountId);
        }

        return response()->json('Loan payment deleted Successfully');
    }

    private function addLoanPaymentDistribution($loanPaymentId, $loanId, $amount, $type)
    {
        $addLoanPaymentDistribution = new LoanPaymentDistribution();
        $addLoanPaymentDistribution->loan_payment_id = $loanPaymentId;
        $addLoanPaymentDistribution->loan_id = $loanId;
        $addLoanPaymentDistribution->paid_amount = $amount;
        $addLoanPaymentDistribution->payment_type = $type;
        $addLoanPaymentDistribution->save();
    }
}
