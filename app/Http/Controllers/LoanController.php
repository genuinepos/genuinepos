<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Utils\Converter;
use App\Utils\LoanUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LoanController extends Controller
{
    protected $loanUtil;

    protected $converter;

    public function __construct(
        LoanUtil $loanUtil,
        Converter $converter,
    ) {
        $this->loanUtil = $loanUtil;
        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $loans = '';
            $query = DB::table('loans')
                ->leftJoin('branches', 'loans.branch_id', 'branches.id')
                ->leftJoin('loan_companies', 'loans.loan_company_id', 'loan_companies.id')
                ->leftJoin('accounts', 'loans.account_id', 'accounts.id');

            if ($request->company_id) {
                $query->where('loans.loan_company_id', $request->company_id);
            }

            if ($request->from_date) {
                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('loans.report_date', $date_range); // Final
            }

            $generalSettings = config('generalSettings');
            $converter = $this->converter;

            $loans = $query->select(
                'loans.*',
                'loan_companies.name as c_name',
                'accounts.name as ac_name',
                'accounts.account_number as ac_number',
                'branches.name as b_name',
                'branches.branch_code as b_code',
            )->where('loans.branch_id', auth()->user()->branch_id)
                ->orderBy('loans.report_date', 'desc');

            return DataTables::of($loans)
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" id="view" href="' . route('accounting.loan.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';

                    // $html .= '<a class="dropdown-item" href="' . route('accounting.loan.edit', [$row->id]) . '" id="edit_loan"><i class="far fa-edit text-primary"></i> Edit</a>';

                    $html .= '<a class="dropdown-item" id="delete_loan" href="' . route('accounting.loan.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })->editColumn('report_date', function ($row) use ($generalSettings) {
                    return date($generalSettings['business_or_shop__date_format'], strtotime($row->report_date));
                })->editColumn('branch', function ($row) use ($generalSettings) {
                    if ($row->b_name) {
                        return $row->b_name . '/' . $row->b_code . '(<b>BL</b>)';
                    } else {
                        return $generalSettings['business_or_shop__business_name'] . '(<b>HO</b>)';
                    }
                })->editColumn('type', function ($row) {
                    if ($row->type == 1) {
                        return '<span class="text-success"><strong>Loan&Advance</strong></span>';
                    } else {
                        return '<span class="text-danger"><strong>Loan&Liability</strong></span>';
                    }
                })->editColumn('loan_by', function ($row) {
                    if ($row->loan_by) {
                        return $row->loan_by;
                    } else {
                        return 'Cash Loan pay.';
                    }
                })->editColumn('loan_amount', fn ($row) => $this->converter->format_in_bdt($row->loan_amount))
                ->editColumn('due', fn ($row) => $this->converter->format_in_bdt($row->due))
                ->editColumn('total_paid', function ($row) use ($converter) {
                    if ($row->type == 1) {
                        return $converter->format_in_bdt($row->total_receive);
                    } else {
                        return $converter->format_in_bdt($row->total_paid);
                    }
                })->rawColumns(['report_date', 'branch', 'type', 'loan_by', 'loan_amount', 'due', 'total_paid', 'action'])->smart(true)->make(true);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $loanAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->whereIn('account_type', [10, 13, 14])
            ->orderBy('account_type', 'desc')
            ->get(['accounts.id', 'accounts.name', 'account_type']);

        return view('accounting.loans.index', compact('branches', 'accounts', 'loanAccounts'));
    }

    public function store(Request $request)
    {
        //return $request->all();
        $this->validate($request, [
            'company_id' => 'required',
            'type' => 'required',
            'loan_amount' => 'required',
            'loan_account_id' => 'required',
            'account_id' => 'required',
            'date' => 'required',
        ], [
            'company_id.required' => 'Company field is required.',
            'loan_account_id.required' => 'Loan A/C field is required.',
            'account_id.required' => 'Debit/Credit A/C field is required.',
        ]);

        // generate reference no
        $refId = 1;

        $prefix = $request->type == 1 ? 'LA' : 'LL';
        $addLoan = new Loan();
        $addLoan->reference_no = $prefix . $refId;
        $addLoan->loan_account_id = $request->loan_account_id;
        $addLoan->branch_id = auth()->user()->branch_id;
        $addLoan->loan_company_id = $request->company_id;
        $addLoan->type = $request->type;
        $addLoan->loan_amount = $request->loan_amount;
        $addLoan->due = $request->loan_amount;
        $addLoan->account_id = $request->account_id;
        $addLoan->loan_by = 'Cash';
        $addLoan->loan_reason = $request->loan_reason;
        $addLoan->created_user_id = auth()->id();
        $addLoan->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addLoan->save();

        if ($request->type == 1) {

            $this->loanUtil->adjustCompanyLoanAdvanceAmount($request->company_id);
            // Add Loan A/C ledger

            // Add Bank/Cash-in-hand A/C ledger
        } else {

            $this->loanUtil->adjustCompanyLoanLiabilityAmount($request->company_id);
            // Add Loan A/C ledger

            // Add Bank/Cash-in-hand A/C ledger
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

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $loanAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->whereIn('account_type', [13, 14])
            ->orderBy('accounts.account_type', 'desc')
            ->get(['accounts.id', 'accounts.name', 'account_type']);

        return view('accounting.loans.ajax_view.editLoan', compact('loan', 'companies', 'accounts', 'loanAccounts'));
    }

    public function update(Request $request, $loanId)
    {
        //return $request->all();
        $this->validate($request, [
            'company_id' => 'required',
            'type' => 'required',
            'loan_amount' => 'required',
            'loan_account_id' => 'required',
            'account_id' => 'required',
            'date' => 'required',
        ], [
            'company_id.required' => 'Company field is required.',
            'loan_account_id.required' => 'Loan A/C field is required.',
            'account_id.required' => 'Debit/Credit A/C field is required.',
        ]);

        $updateLoan = Loan::where('id', $loanId)->first();
        $updateLoan->loan_company_id = $request->company_id;
        $updateLoan->loan_account_id = $request->loan_account_id;
        $updateLoan->type = $request->type;
        $updateLoan->loan_amount = $request->loan_amount;
        $updateLoan->account_id = $request->account_id;
        $updateLoan->loan_reason = $request->loan_reason;
        $updateLoan->created_user_id = auth()->id();
        $updateLoan->report_date = date('Y-m-d', strtotime($request->date));
        $updateLoan->save();
        $this->loanUtil->loanAmountAdjustment($updateLoan);

        if ($request->type == 1) {
            $this->loanUtil->adjustCompanyLoanAdvanceAmount($request->company_id);

            // Update loan A/C Ledger

            // Update Bank/Cash-In-Hand A/C Ledger
        } else {
            $this->loanUtil->adjustCompanyLoanLiabilityAmount($request->company_id);
            // Update loan A/C Ledger

            // Update Bank/Cash-In-Hand A/C Ledger
        }

        return response()->json('Loan updated Successfully');
    }

    public function delete(Request $request, $loanId)
    {
        $loan = Loan::where('id', $loanId)->first();
        $storeAccountId = $loan->account_id;
        $storeLoanAccountId = $loan->loan_account_id;
        $storedType = $loan->type;
        $storedCompanyId = $loan->loan_company_id;

        if ($loan->total_paid > 0) {
            return response()->json(['errorMsg' => 'This loan can not delete. Some or full amount has been paid on this loan.']);
        }

        if ($loan->total_receive > 0) {
            return response()->json(['errorMsg' => 'This loan can not delete. Some or full amount has been received on this loan.']);
        }

        $loan->delete();
        if ($storedType == 1) {
            $this->loanUtil->adjustCompanyLoanAdvanceAmount($storedCompanyId);
        } else {
            $this->loanUtil->adjustCompanyLoanLiabilityAmount($storedCompanyId);
        }

        DB::statement('ALTER TABLE loans AUTO_INCREMENT = 1');

        return response()->json('Loan deleted Successfully');
    }

    public function allCompaniesForForm()
    {
        return DB::table('loan_companies')
            ->where('branch_id', auth()->user()->branch_id)
            ->select('id', 'name')->get();
    }

    public function show($loanId)
    {
        $loan = Loan::with(['company', 'account'])->where('id', $loanId)->first();

        return view('accounting.loans.ajax_view.loanDetails', compact('loan'));
    }

    public function loanPrint(Request $request)
    {
        $loans = '';
        $fromDate = '';
        $toDate = '';
        $company_id = $request->company_id;
        $query = DB::table('loans')
            ->leftJoin('branches', 'loans.branch_id', 'branches.id')
            ->leftJoin('loan_companies', 'loans.loan_company_id', 'loan_companies.id')
            ->leftJoin('accounts', 'loans.account_id', 'accounts.id');

        if ($request->company_id) {
            $query->where('loans.loan_company_id', $request->company_id);
        }

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('loans.report_date', $date_range); // Final
        }

        $generalSettings = config('generalSettings');

        $loans = $query->select(
            'loans.*',
            'loan_companies.name as c_name',
            'accounts.name as ac_name',
            'accounts.account_number as ac_number',
            'branches.name as b_name',
            'branches.branch_code as b_code',
        )->where('loans.branch_id', auth()->user()->branch_id)
            ->orderBy('loans.report_date', 'desc')->get();

        return view('reports.loan_report.print', compact('loans', 'fromDate', 'toDate', 'company_id'));
    }
}
