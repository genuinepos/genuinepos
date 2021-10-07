<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\CashFlow;
use App\Utils\AccountUtil;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $accountUtil;
    public function __construct(AccountUtil $accountUtil)
    {
        $this->accountUtil = $accountUtil;
        $this->middleware('auth:admin_and_user');
    }

    // Bank main page/index page
    public function index()
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        return view('accounting.accounts.index');
    }

    // Get all banks by ajax
    public function allAccounts()
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $accounts = Account::with(['bank', 'account_type', 'admin', 'admin.role'])->orderBy('id', 'DESC')->where('status', 1)->get();
        return view('accounting.accounts.ajax_view.account_list', compact('accounts'));
    }

    //Get account book
    public function accountBook($accountId)
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        $account = Account::with(['bank', 'account_type', 'cash_flows'])->where('id', $accountId)->first();
        return view('accounting.accounts.account_book', compact('account'));
    }

    // Store bank
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'account_number' => 'required',
            'bank_id' => 'required',
        ]);

        $addAccount = Account::insertGetId([
            'name' => $request->name,
            'account_number' => $request->account_number,
            'bank_id' => $request->bank_id,
            'account_type_id' => $request->account_type_id,
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0,
            'balance' => $request->opening_balance ? $request->opening_balance : 0,
            'credit' => $request->opening_balance ? $request->opening_balance : 0,
            'remark' => $request->remark,
            'admin_id' => auth()->user()->id,
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

    // Update bank
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'account_number' => 'required',
            'bank_id' => 'required',
        ]);

        $updateBank = Account::where('id', $request->id)->first();
        $updateBank->update([
            'name' => $request->name,
            'account_number' => $request->account_number,
            'bank_id' => $request->bank_id,
            'account_type_id' => $request->account_type_id,
            'remark' => $request->remark,
        ]);

        return response()->json('Account updated successfully');
    }

    public function delete(Request $request, $accountId)
    {
        return response()->json('Feature is disabled in this demo');
        $deleteAccount = Account::find($accountId);
        if (!is_null($deleteAccount)) {
            $deleteAccount->delete();
        }

        return response()->json('Account deleted successfully');
    }

    public function allBanks()
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        $banks = Bank::select('id', 'name', 'branch_name')->get();
        return response()->json($banks);
    }

    public function allAccountTypes()
    {
        $types = AccountType::where('status', 1)->get();
        return response()->json($types);
    }

    public function allFromAccount()
    {
        $accounts = Account::orderBy('id', 'DESC')->where('status', 1)->get();
        return response()->json($accounts);
    }

    public function fundTransfer(Request $request)
    {
        $cashFlow1 = new CashFlow();
        $cashFlow1->account_id = $request->sender_account_id;
        $cashFlow1->receiver_account_id = $request->receiver_account_id;
        $cashFlow1->debit = $request->amount;
        $cashFlow1->transaction_type = 4;
        $cashFlow1->cash_type = 1;
        $cashFlow1->date = $request->date;
        $cashFlow1->report_date = date('Y-m-d', strtotime($request->date));
        $cashFlow1->month = date('F');
        $cashFlow1->year = date('Y');
        $cashFlow1->admin_id = auth()->user()->id;
        $cashFlow1->save();
        $cashFlow1->balance = $this->accountUtil->adjustAccountBalance($request->sender_account_id);
        $cashFlow1->save();

        $cashFlow2 = new CashFlow();
        $cashFlow2->account_id = $request->receiver_account_id;
        $cashFlow2->sender_account_id = $request->sender_account_id;
        $cashFlow2->credit = $request->amount;
        $cashFlow2->transaction_type = 4;
        $cashFlow2->cash_type = 2;
        $cashFlow2->date = $request->date;
        $cashFlow2->report_date = date('Y-m-d', strtotime($request->date));
        $cashFlow2->month = date('F');
        $cashFlow2->year = date('Y');
        $cashFlow2->related_cash_flow_id = $cashFlow1->id;
        $cashFlow2->admin_id = auth()->user()->id;
        $cashFlow2->save();
        $cashFlow2->balance = $this->accountUtil->adjustAccountBalance($request->receiver_account_id);
        $cashFlow2->save();

        $cashFlow1->related_cash_flow_id = $cashFlow2->id;
        $cashFlow1->save();

        return response()->json('Successfully account fund transfer is created.');
    }

    public function deposit(Request $request)
    {
        $cashFlow1 = new CashFlow();
        $cashFlow1->account_id = $request->receiver_account_id;
        $cashFlow1->sender_account_id = $request->sender_account_id ? $request->sender_account_id : NULL;
        $cashFlow1->credit = $request->amount;
        $cashFlow1->transaction_type = 5;
        $cashFlow1->cash_type = 2;
        $cashFlow1->date = $request->date;
        $cashFlow1->report_date = date('Y-m-d', strtotime($request->date));
        $cashFlow1->month = date('F');
        $cashFlow1->year = date('Y');
        $cashFlow1->admin_id = auth()->user()->id;
        $cashFlow1->save();
        $cashFlow1->balance = $this->accountUtil->adjustAccountBalance($request->receiver_account_id);
        $cashFlow1->save();

        if ($request->sender_account_id) {
            $cashFlow2 = new CashFlow();
            $cashFlow2->account_id = $request->sender_account_id;
            $cashFlow2->receiver_account_id = $request->receiver_account_id;
            $cashFlow2->debit = $request->amount;
            $cashFlow2->transaction_type = 4;
            $cashFlow2->cash_type = 1;
            $cashFlow2->date = $request->date;
            $cashFlow2->report_date = date('Y-m-d', strtotime($request->date));
            $cashFlow2->month = date('F');
            $cashFlow2->year = date('Y');
            $cashFlow2->related_cash_flow_id = $cashFlow1->id;
            $cashFlow2->admin_id = auth()->user()->id;
            $cashFlow2->save();
            $cashFlow2->balance = $this->accountUtil->adjustAccountBalance($request->sender_account_id);
            $cashFlow2->save();

            $cashFlow1->related_cash_flow_id = $cashFlow2->id;
            $cashFlow1->save();
        }
        return response()->json('Successfully account deposit is created.');
    }

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
            'loan.company',
        ])->where('account_id', $accountId);

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
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
                $realatedCashFlow = CashFlow::where('id', $deleteCashflow->related_cash_flow_id)->first();
                $realatedCashFlow->delete();
            }
            $deleteCashflow->delete();
        }
        return response()->json('Successfully cashflow is deleted');
    }
}
