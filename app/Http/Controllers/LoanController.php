<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Account;
use App\Models\LoanCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $loans = DB::table('loans')
                ->leftJoin('branches', 'loans.branch_id', 'branches.id')
                ->leftJoin('loan_companies', 'loans.loan_company_id', 'loan_companies.id')
                ->leftJoin('accounts', 'loans.account_id', 'accounts.id')
                ->select(
                    'loans.*',
                    'loan_companies.name as c_name',
                    'accounts.name as ac_name',
                    'accounts.account_number as ac_number',
                    'branches.name as b_name',
                    'branches.branch_code as b_code',
                )->get();
            
            return DataTables::of($loans)
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="' . route('accounting.loan.show', [$row->id]) . '" id="show_loan"><i class="far fa-eye text-primary"></i> View</a>';
                    $html .= '<a class="dropdown-item" href="' . route('accounting.loan.edit', [$row->id]) . '" id="edit_loan"><i class="far fa-edit text-primary"></i> Edit</a>';

                    if ($row->type == 1) {
                        $html .= '<a class="dropdown-item" id="receive_due_loan" href="#"><i class="far fa-money-bill-alt text-primary"></i> Receive Amount</a>';
                    }else {
                        $html .= '<a class="dropdown-item" id="pay_due_loan" href="#"><i class="far fa-money-bill-alt text-primary"></i> Pay Amount</a>';
                    }
                    
                    $html .= '<a class="dropdown-item" id="delete_loan" href="' . route('accounting.loan.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })->editColumn('report_date', function ($row) use ($generalSettings) {
                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->report_date));
                })->editColumn('branch', function ($row) use ($generalSettings) {
                    if ($row->b_name) {
                        return $row->b_name . '/' . $row->b_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })->editColumn('type', function ($row) {
                    if ($row->type == 1) {
                        return '<span class="text-success"><strong>Pay Loan</strong></span>';
                    }else {
                        return '<span class="text-danger"><strong>Get Loan</strong></span>';
                    }
                })->editColumn('loan_amount', function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['currency'].' '. $row->loan_amount;
                })->editColumn('due', function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['currency'].' '. $row->due;
                })->editColumn('total_paid', function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['currency'].' '. $row->total_paid;
                })->rawColumns(['report_date', 'branch', 'type', 'loan_amount', 'due', 'total_paid', 'action'])->smart(true)->make(true);
        }
        $companies = DB::table('loan_companies')->select('id', 'name')->get();
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number')->get();
        return view('accounting.loans.index', compact('companies', 'accounts'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'company_id' => 'required',
            'type' => 'required',
            'loan_amount' => 'required',
            'account_id' => 'required'
        ], [
            'company_id.required' => 'Company field is required.',
            'account_id.required' => 'Account field is required.',
        ]);

        // generate reference no
        $i = 4;
        $a = 0;
        $refId = '';
        while ($a < $i) {
            $refId .= rand(1, 9);
            $a++;
        }

        $prefix = $request->type == 1 ? 'LP' : 'LG';
        $addLoan = new Loan();
        $addLoan->reference_no = $prefix . date('Y') . $refId;
        $addLoan->branch_id = auth()->user()->branch_id;
        $addLoan->loan_company_id = $request->company_id;
        $addLoan->type = $request->type;
        $addLoan->loan_amount = $request->loan_amount;
        $addLoan->due = $request->due;
        $addLoan->account_id = $request->account_id;
        $addLoan->loan_reason = $request->loan_reason;
        $addLoan->created_user_id = auth()->id();
        $addLoan->report_date = date('Y-m-d');
        $addLoan->save();

        $addCompanyLoanAmount = LoanCompany::where('id', $request->company_id)->first();
        if ($request->type == 1) {
            $addCompanyLoanAmount->pay_loan_amount += (float)$request->loan_amount;
            $addCompanyLoanAmount->pay_loan_due += (float)$request->loan_amount;
        } else {
            $addCompanyLoanAmount->get_loan_amount += (float)$request->loan_amount;
            $addCompanyLoanAmount->get_loan_due += (float)$request->loan_amount;
        }
        $addCompanyLoanAmount->save();

        $account = Account::where('id', $request->account_id)->first();
        if ($account) {
            if ($request->type == 1) {
                $account->balance -= (float)$request->loan_amount;
                $account->debit += (float)$request->loan_amount;
                $account->save();
            } else {
                $account->balance += (float)$request->loan_amount;
                $account->credit += (float)$request->loan_amount;
                $account->save();
            }

            // Cashflow will be go here.
        }

        return response()->json('Loan created Successfully');
    }

    public function edit($loanId)
    {
        $loan = DB::table('loans')->where('id', $loanId)->first();
        if ($loan->total_paid > 0) {
            return response()->json(['errorMsg' => 'This loan is not editable. Some or full amount has been paid/received on this loan.']);
        }

        $companies = DB::table('loan_companies')->select('id', 'name')->get();
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number')->get();
        return view('accounting.loans.ajax_view.editLoan', compact('loan', 'companies', 'accounts'));
    }

    public function update(Request $request, $loanId)
    {
        $this->validate($request, [
            'company_id' => 'required',
            'type' => 'required',
            'loan_amount' => 'required',
            'account_id' => 'required'
        ], [
            'company_id.required' => 'Company field is required.',
            'account_id.required' => 'Account field is required.',
        ]);

        $updateLoan =  Loan::where('id', $loanId)->first();
    

        $previousCompany = LoanCompany::where('id', $updateLoan->loan_company_id)->first();
        if ($previousCompany) {
            if ($updateLoan->type == 1) {
                $previousCompany->pay_loan_amount -= $updateLoan->loan_amount;
                $previousCompany->pay_loan_due -= $updateLoan->loan_amount;
                $previousCompany->save();
            } else {
                $previousCompany->get_loan_amount -= $updateLoan->loan_amount;
                $previousCompany->get_loan_due -= $updateLoan->loan_amount;
                $previousCompany->save();
            }
        }

        $addCompanyLoanAmount = LoanCompany::where('id', $request->company_id)->first();
        if ($request->type == 1) {
            $addCompanyLoanAmount->pay_loan_amount = $addCompanyLoanAmount->pay_loan_amount + $request->loan_amount;
            $addCompanyLoanAmount->pay_loan_due = $addCompanyLoanAmount->pay_loan_due + $request->loan_amount;
            $addCompanyLoanAmount->save();
        } else {
            $addCompanyLoanAmount->get_loan_amount = $addCompanyLoanAmount->get_loan_amount + $request->loan_amount;
            $addCompanyLoanAmount->get_loan_due = $addCompanyLoanAmount->get_loan_amount + $request->loan_amount;
            $addCompanyLoanAmount->save();
        }
        
        $previousAccount = Account::where('id', $updateLoan->account_id)->first();
        if ($previousAccount) {
            if ($updateLoan->type == 1) {
                $previousAccount->balance += $updateLoan->loan_amount;
                $previousAccount->debit -= $updateLoan->loan_amount;
                $previousAccount->save();
            } else {
                $previousAccount->balance -= $updateLoan->loan_amount;
                $previousAccount->credit -= $updateLoan->loan_amount;
                $previousAccount->save();
            }
        }

        $presentAccount = Account::where('id', $request->account_id)->first();
        if ($presentAccount) {
            if ($request->type == 1) {
                $presentAccount->balance -= (float)$request->loan_amount;
                $presentAccount->debit += (float)$request->loan_amount;
                $presentAccount->save();
            } else {
                $presentAccount->balance += (float)$request->loan_amount;
                $presentAccount->credit += (float)$request->loan_amount;
                $presentAccount->save();
            }
            // Cashflow will be go here.
        }

        $updateLoan->loan_company_id = $request->company_id;
        $updateLoan->type = $request->type;
        $updateLoan->loan_amount = $request->loan_amount;
        $updateLoan->due = $request->loan_amount;
        $updateLoan->account_id = $request->account_id;
        $updateLoan->loan_reason = $request->loan_reason;
        $updateLoan->created_user_id = auth()->id();
        $updateLoan->report_date = date('Y-m-d');
        $updateLoan->save();

        return response()->json('Loan updated Successfully');
    }

    public function delete(Request $request, $loanId)
    {
        $loan = Loan::where('id', $loanId)->first();
        if ($loan->total_paid > 0) {
            return response()->json(['errorMsg' => 'This loan can not delete. Some or full amount has been paid/received on this loan.']);
        }

        $company = LoanCompany::where('id', $loan->loan_company_id)->first();
        if ($company) {
            if ($loan->type == 1) {
                $company->pay_loan_amount -= $loan->loan_amount;
                $company->pay_loan_due -= $loan->loan_amount;
                $company->save();
            } else {
                $company->get_loan_amount -= $loan->loan_amount;
                $company->get_loan_due -= $loan->loan_amount;
                $company->save();
            }
        }

        $previousAccount = Account::where('id', $loan->account_id)->first();
        if ($previousAccount) {
            if ($loan->type == 1) {
                $previousAccount->balance += $loan->loan_amount;
                $previousAccount->debit -= $loan->loan_amount;
                $previousAccount->save();
            } else {
                $previousAccount->balance -= $loan->loan_amount;
                $previousAccount->credit -= $loan->loan_amount;
                $previousAccount->save();
            }
        }

        $loan->delete();
        return response()->json('Loan deleted Successfully');
    }
}
