<?php

namespace App\Http\Controllers;

use App\Utils\Util;
use App\Models\Bank;
use App\Models\Account;
use App\Models\CashFlow;
use App\Utils\Converter;
use App\Utils\AccountUtil;
use App\Models\AccountType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AccountController extends Controller
{
    protected $accountUtil;
    protected $util;
    protected $converter;
    public function __construct(AccountUtil $accountUtil, Util $util, Converter $converter)
    {
        $this->accountUtil = $accountUtil;
        $this->util = $util;
        $this->converter = $converter;
        $this->middleware('auth:admin_and_user');
    }

    // Bank main page/index page
    public function index(Request $request)
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $accounts = '';
            $query = DB::table('accounts')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->leftJoin('branches', 'accounts.branch_id', 'branches.id');

            if ($request->account_type) {
                $query = $query->where('accounts.account_type', $request->account_type);
            }

            $accounts = $query->select('accounts.*', 'banks.name as b_name', 'banks.branch_name as b_branch')
            ->orderBy('accounts.account_type', 'asc');

            return DataTables::of($accounts)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a id="edit" class="dropdown-item" href="' . route('accounting.accounts.edit', [$row->id]) . '" ><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" href="' . route('accounting.accounts.book', [$row->id]) . '"><i class="fas fa-book text-primary"></i> Account Book</a>';
                    $html .= '<a class="dropdown-item" href="' . route('accounting.accounts.delete', [$row->id]) . '" id="delete"><i class="fas fa-trash text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('ac_number', fn ($row) => $row->account_number ? $row->account_number : 'Not Applicable')
                ->editColumn('bank', fn ($row) => $row->b_name ? $row->b_name . ' (' . $row->b_branch . ')' : 'Not Applicable')
                ->editColumn('account_type', fn ($row) => '<b>' . $this->util->accountType($row->account_type) . '</b>')
                ->editColumn('opening_balance', fn ($row) => $this->converter->format_in_bdt($row->opening_balance))
                ->editColumn('balance', fn ($row) => $this->converter->format_in_bdt($row->balance))
                ->rawColumns(['action', 'ac_number', 'bank', 'account_type', 'opening_balance', 'balance'])
                ->make(true);
        }

        $banks = DB::table('banks')->get();
        return view('accounting.accounts.index', compact('banks'));
    }

    //Get account book
    public function accountBook($accountId)
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        $account = Account::with(['bank', 'cash_flows'])->where('id', $accountId)->first();
        return view('accounting.accounts.account_book', compact('account'));
    }

    // Store bank
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'account_type' => 'required',
        ]);

        if ($request->account_type == 2) {
            $this->validate($request, [
                'bank_id' => 'required',
                'account_number' => 'required',
            ]);
        }

        $addAccount = Account::insertGetId([
            'name' => $request->name,
            'account_number' => $request->account_type == 2 ? $request->account_number : null,
            'bank_id' => $request->account_type == 2 ? $request->bank_id : null,
            'account_type' => $request->account_type,
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0,
            'balance' => $request->opening_balance ? $request->opening_balance : 0,
            'debit' => $request->opening_balance ? $request->opening_balance : 0,
            'remark' => $request->remark,
            'admin_id' => auth()->user()->id,
            'branch_id' => auth()->user()->branch_id,
        ]);

        $addCashflow = new CashFlow();
        $addCashflow->account_id = $addAccount;
        $addCashflow->transaction_type = 7;
        $addCashflow->cash_type = 2;
        $addCashflow->credit = $request->opening_balance ? $request->opening_balance : 0;
        $addCashflow->report_date = date('Y-m-d');
        $addCashflow->date = date('Y-m-d');
        $addCashflow->month = date('F');
        $addCashflow->year = date('Y');
        $addCashflow->admin_id = auth()->user()->id;
        $addCashflow->save();
        $addCashflow->balance = $this->accountUtil->adjustAccountBalance($addAccount);
        $addCashflow->save();

        return response()->json('Account created successfully');
    }

    public function edit($id)
    {
        $account = DB::table('accounts')->where('id', $id)->first();
        $banks = DB::table('banks')->get();
        return view('accounting.accounts.ajax_view.edit_account', compact('account', 'banks'));
    }

    // Update bank
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        if ($request->account_type == 2) {
            $this->validate($request, [
                'bank_id' => 'required',
                'account_number' => 'required',
            ]);
        }

        $updateBank = Account::where('id', $id)->first();
        $updateBank->update([
            'name' => $request->name,
            'account_number' => $request->account_type == 2 ? $request->account_number : null,
            'bank_id' => $request->account_type == 2 ? $request->bank_id : null,
            'account_type' => $request->account_type,
            'remark' => $request->remark,
        ]);

        return response()->json('Account updated successfully');
    }

    public function delete(Request $request, $accountId)
    {
        $deleteAccount = Account::find($accountId);
        if (!is_null($deleteAccount)) {
            $deleteAccount->delete();
        }

        return response()->json('Account deleted successfully');
    }

    // public function fundTransfer(Request $request)
    // {
    //     $cashFlow1 = new CashFlow();
    //     $cashFlow1->account_id = $request->sender_account_id;
    //     $cashFlow1->receiver_account_id = $request->receiver_account_id;
    //     $cashFlow1->debit = $request->amount;
    //     $cashFlow1->transaction_type = 4;
    //     $cashFlow1->cash_type = 1;
    //     $cashFlow1->date = $request->date;
    //     $cashFlow1->report_date = date('Y-m-d', strtotime($request->date));
    //     $cashFlow1->month = date('F');
    //     $cashFlow1->year = date('Y');
    //     $cashFlow1->admin_id = auth()->user()->id;
    //     $cashFlow1->save();
    //     $cashFlow1->balance = $this->accountUtil->adjustAccountBalance($request->sender_account_id);
    //     $cashFlow1->save();

    //     $cashFlow2 = new CashFlow();
    //     $cashFlow2->account_id = $request->receiver_account_id;
    //     $cashFlow2->sender_account_id = $request->sender_account_id;
    //     $cashFlow2->credit = $request->amount;
    //     $cashFlow2->transaction_type = 4;
    //     $cashFlow2->cash_type = 2;
    //     $cashFlow2->date = $request->date;
    //     $cashFlow2->report_date = date('Y-m-d', strtotime($request->date));
    //     $cashFlow2->month = date('F');
    //     $cashFlow2->year = date('Y');
    //     $cashFlow2->related_cash_flow_id = $cashFlow1->id;
    //     $cashFlow2->admin_id = auth()->user()->id;
    //     $cashFlow2->save();
    //     $cashFlow2->balance = $this->accountUtil->adjustAccountBalance($request->receiver_account_id);
    //     $cashFlow2->save();

    //     $cashFlow1->related_cash_flow_id = $cashFlow2->id;
    //     $cashFlow1->save();

    //     return response()->json('Successfully account fund transfer is created.');
    // }

    // public function deposit(Request $request)
    // {
    //     $cashFlow1 = new CashFlow();
    //     $cashFlow1->account_id = $request->receiver_account_id;
    //     $cashFlow1->sender_account_id = $request->sender_account_id ? $request->sender_account_id : NULL;
    //     $cashFlow1->credit = $request->amount;
    //     $cashFlow1->transaction_type = 5;
    //     $cashFlow1->cash_type = 2;
    //     $cashFlow1->date = $request->date;
    //     $cashFlow1->report_date = date('Y-m-d', strtotime($request->date));
    //     $cashFlow1->month = date('F');
    //     $cashFlow1->year = date('Y');
    //     $cashFlow1->admin_id = auth()->user()->id;
    //     $cashFlow1->save();
    //     $cashFlow1->balance = $this->accountUtil->adjustAccountBalance($request->receiver_account_id);
    //     $cashFlow1->save();

    //     if ($request->sender_account_id) {
    //         $cashFlow2 = new CashFlow();
    //         $cashFlow2->account_id = $request->sender_account_id;
    //         $cashFlow2->receiver_account_id = $request->receiver_account_id;
    //         $cashFlow2->debit = $request->amount;
    //         $cashFlow2->transaction_type = 4;
    //         $cashFlow2->cash_type = 1;
    //         $cashFlow2->date = $request->date;
    //         $cashFlow2->report_date = date('Y-m-d', strtotime($request->date));
    //         $cashFlow2->month = date('F');
    //         $cashFlow2->year = date('Y');
    //         $cashFlow2->related_cash_flow_id = $cashFlow1->id;
    //         $cashFlow2->admin_id = auth()->user()->id;
    //         $cashFlow2->save();
    //         $cashFlow2->balance = $this->accountUtil->adjustAccountBalance($request->sender_account_id);
    //         $cashFlow2->save();

    //         $cashFlow1->related_cash_flow_id = $cashFlow2->id;
    //         $cashFlow1->save();
    //     }
    //     return response()->json('Successfully account deposit is created.');
    // }

    public function accountCashflows($accountId)
    {
        $accountCashFlows = CashFlow::with([
            'sender_account',
            'receiver_account',
            'sale_payment',
            'sale_payment.customer',
            'sale_payment.sale',
            'purchase_payment',
            'purchase_payment.supplier',
            'purchase_payment.purchase',
            'expanse_payment',
            'expanse_payment.expense',
            'money_receipt',
            'money_receipt.customer',
            'payroll',
            'payroll_payment',
            'loan',
            'loan_payment',
            'loan_payment.branch',
            'loan_payment.company',
            'loan.company',
        ])->where('account_id', $accountId)->orderBy('id', 'desc')->get();
        return view('accounting.accounts.ajax_view.account_cash_flow_list', compact('accountCashFlows'));
    }

    public function accountCashflowFilter(Request $request, $accountId)
    {
        $filterAccountCashFlows = '';
        $query = CashFlow::with([
            'sender_account',
            'receiver_account',
            'sale_payment',
            'sale_payment.customer',
            'sale_payment.sale',
            'purchase_payment',
            'purchase_payment.supplier',
            'purchase_payment.purchase',
            'expanse_payment',
            'expanse_payment.expense',
            'money_receipt',
            'money_receipt.customer',
            'payroll',
            'payroll_payment',
            'loan',
            'loan_payment',
            'loan_payment.branch',
            'loan_payment.company',
            'loan.company',
        ])->where('account_id', $accountId);

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
            $query->whereBetween('report_date', $date_range); // Final
        }

        if ($request->transaction_type) {
            $query->where('cash_type', $request->transaction_type);
        }
        $filterAccountCashFlows = $query->orderBy('id', 'desc')->get();
        return view('accounting.accounts.ajax_view.filter_account_cash_flow_list', compact('filterAccountCashFlows'));
    }

    public function deleteCashFlow($cashFlowId)
    {
        $deleteCashflow = CashFlow::with('account', 'sender_account', 'receiver_account')
            ->where('id', $cashFlowId)->first();
        if (!is_null($deleteCashflow)) {
            if ($deleteCashflow->transaction_type == 4) {
                if ($deleteCashflow->cash_type == 1) {
                    $deleteCashflow->account->debit -= $deleteCashflow->debit;
                    $deleteCashflow->account->balance += $deleteCashflow->debit;
                    $deleteCashflow->account->save();

                    $deleteCashflow->receiver_account->credit -= $deleteCashflow->debit;
                    $deleteCashflow->receiver_account->balance -= $deleteCashflow->debit;
                    $deleteCashflow->receiver_account->save();
                } elseif ($deleteCashflow->cash_type == 2) {
                    $deleteCashflow->account->credit -= $deleteCashflow->credit;
                    $deleteCashflow->account->balance -= $deleteCashflow->debit;
                    $deleteCashflow->account->save();

                    $deleteCashflow->sender_account->debit -= $deleteCashflow->credit;
                    $deleteCashflow->sender_account->balance += $deleteCashflow->debit;
                    $deleteCashflow->sender_account->save();
                }
            } elseif ($deleteCashflow->transaction_type == 5) {
                $deleteCashflow->account->credit -= $deleteCashflow->credit;
                $deleteCashflow->account->balance -= $deleteCashflow->debit;
                $deleteCashflow->account->save();

                if ($deleteCashflow->sender_account) {
                    $deleteCashflow->sender_account->debit -= $deleteCashflow->credit;
                    $deleteCashflow->sender_account->balance += $deleteCashflow->balance;
                    $deleteCashflow->sender_account->save();
                }
            }
            if ($deleteCashflow->related_cash_flow_id) {
                $relatedCashFlow = CashFlow::where('id', $deleteCashflow->related_cash_flow_id)->first();
                $relatedCashFlow->delete();
            }
            $deleteCashflow->delete();
        }
        return response()->json('Successfully cashflow is deleted');
    }
}
