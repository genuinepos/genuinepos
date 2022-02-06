<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Utils\Util;
use App\Models\Bank;
use App\Models\Account;
use App\Models\CashFlow;
use App\Utils\Converter;
use App\Utils\AccountUtil;
use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Models\AccountBranch;
use App\Models\AccountLedger;
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

            $generalSettings = DB::table('general_settings')->first();
            $accounts = '';
            $query = DB::table('account_branches')
                ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
                ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
                ->leftJoin('branches', 'account_branches.branch_id', 'branches.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('account_branches.branch_id', NULL);
                } else {
                    $query->where('account_branches.branch_id', $request->branch_id);
                }
            }

            if ($request->account_type) {
                $query = $query->where('accounts.account_type', $request->account_type);
            }

            $query->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.opening_balance',
                'accounts.balance',
                'accounts.account_type',
                'banks.name as b_name',
                'banks.branch_name as b_branch',
                'branches.name as branch_name',
                'branches.branch_code',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $accounts = $query->orderBy('accounts.account_type', 'asc');
            } else {
                $accounts = $query->where('account_branches.branch_id', auth()->user()->branch_id)
                    ->orderBy('accounts.account_type', 'asc');
            }

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
                ->editColumn('branch', fn ($row) => '<b>' . ($row->branch_name ? $row->branch_name . '/' . $row->branch_code : json_decode($generalSettings->business, true)['shop_name']) . '</b>')
                ->editColumn('opening_balance', fn ($row) => $this->converter->format_in_bdt($row->opening_balance))
                ->editColumn('balance', fn ($row) => $this->converter->format_in_bdt($row->balance))
                ->rawColumns(['action', 'ac_number', 'bank', 'account_type', 'branch', 'opening_balance', 'balance'])
                ->make(true);
        }

        $banks = DB::table('banks')->get();
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('accounting.accounts.index', compact('banks', 'branches'));
    }

    //Get account book
    public function accountBook(Request $request, $accountId)
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $settings = DB::table('general_settings')->first();

            $accountUtil = $this->accountUtil;

            $ledgers = '';

            $query = DB::table('account_ledgers')->where('account_ledgers.account_id', $accountId)
                ->leftJoin('expanses', 'account_ledgers.expense_id', 'expanses.id')
                ->leftJoin('expanse_payments', 'account_ledgers.expense_payment_id', 'expanses.id')
                ->leftJoin('sales', 'account_ledgers.sale_id', 'sales.id')
                ->leftJoin('sale_payments', 'account_ledgers.sale_payment_id', 'sale_payments.id')
                ->leftJoin('supplier_payments', 'account_ledgers.supplier_payment_id', 'supplier_payments.id')
                ->leftJoin('sale_returns', 'account_ledgers.sale_return_id', 'sale_returns.id')
                ->leftJoin('purchases', 'account_ledgers.purchase_id', 'purchases.id')
                ->leftJoin('purchase_payments', 'account_ledgers.purchase_payment_id', 'purchase_payments.id')
                ->leftJoin('customer_payments', 'account_ledgers.customer_payment_id', 'customer_payments.id')
                ->leftJoin('purchase_returns', 'account_ledgers.purchase_return_id', 'purchase_returns.id')
                ->leftJoin('stock_adjustments', 'account_ledgers.adjustment_id', 'stock_adjustments.id')
                ->leftJoin('stock_adjustment_recovers', 'account_ledgers.stock_adjustment_recover_id', 'stock_adjustment_recovers.id')
                // ->leftJoin('hrm_payrolls', 'account_ledgers.payroll_id', 'hrm_payrolls.id')
                ->leftJoin('hrm_payroll_payments', 'account_ledgers.payroll_payment_id', 'hrm_payroll_payments.id')
                ->leftJoin('productions', 'account_ledgers.production_id', 'productions.id')
                ->leftJoin('loans', 'account_ledgers.loan_id', 'loans.id')
                ->leftJoin('loan_payments', 'account_ledgers.loan_payment_id', 'loan_payments.id')
                ->select(
                    'account_ledgers.date',
                    'account_ledgers.voucher_type',
                    'account_ledgers.debit',
                    'account_ledgers.credit',
                    'account_ledgers.running_balance',
                    'expanses.invoice_id as exp_voucher_no',
                    'expanses.note as ex_pur',
                    'expanse_payments.invoice_id as exp_payment_voucher',
                    'expanse_payments.note as expense_payment_pur',
                    'sales.invoice_id as sale_inv_id',
                    'sales.sale_note as sale_pur',
                    'sale_payments.invoice_id as sale_payment_voucher',
                    'sale_payments.note as sale_payment_pur',
                    'supplier_payments.voucher_no as supplier_payment_voucher',
                    'supplier_payments.note as supplier_payment_pur',
                    'sale_returns.invoice_id as sale_return_inv',
                    'sale_returns.date as sale_return_pur',
                    'purchases.invoice_id as purchase_inv_id',
                    'purchases.purchase_note as purchase_pur',
                    'purchase_payments.invoice_id as pur_payment_voucher',
                    'purchase_payments.note as purchase_payment_pur',
                    'customer_payments.voucher_no as customer_payment_voucher',
                    'customer_payments.note as customer_payment_pur',
                    'purchase_returns.invoice_id as pur_return_invoice',
                    'purchase_returns.date as purchase_return_pur',
                    'stock_adjustments.invoice_id as sa_voucher',
                    'stock_adjustments.reason as adjustment_pur',
                    'stock_adjustment_recovers.voucher_no as sar_amt_voucher',
                    'stock_adjustment_recovers.note as sar_pur',
                    'hrm_payroll_payments.reference_no as payroll_pay_voucher',
                    'hrm_payroll_payments.note as payroll_payment_pur',
                    'productions.reference_no as production_voucher',
                    'loans.reference_no as loan_voucher_no',
                    'loans.loan_reason as loan_pur',
                    'loan_payments.voucher_no as loan_payment_voucher',
                    'loan_payments.date as loan_pay_pur',
                )->orderBy('account_ledgers.date', 'asc');

            if ($request->transaction_type) {
                $query->where('account_ledgers.amount_type', $request->transaction_type); // Final
            }

            if ($request->voucher_type) {
                $query->where('account_ledgers.voucher_type', $request->voucher_type); // Final
            }

            if ($request->from_date) {
                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('account_ledgers.date', $date_range); // Final
            }

            $ledgers = $query;

            return DataTables::of($ledgers)
                ->editColumn('date', function ($row) use ($settings) {
                    $dateFormat = json_decode($settings->business, true)['date_format'];
                    $__date_format = str_replace('-', '/', $dateFormat);
                    return date($__date_format, strtotime($row->date));
                })
                ->editColumn('particulars', function ($row) use ($accountUtil) {
                    $type = $accountUtil->voucherType($row->voucher_type);
                    return '<b>' . $type['name'] . '</b>' . ($row->{$type['pur']} ? ' : ' . $row->{$type['pur']} : '');
                    //return '<b>' . $type['name'].'</b>';
                })
                ->editColumn('voucher_no',  function ($row) use ($accountUtil) {
                    $type = $accountUtil->voucherType($row->voucher_type);
                    return $row->{$type['voucher_no']};
                })
                ->editColumn('debit', fn ($row) => '<span class="debit" data-value="' . $row->debit . '">' . $this->converter->format_in_bdt($row->debit) . '</span>')
                ->editColumn('credit', fn ($row) => '<span class="credit" data-value="' . $row->credit . '">' . $this->converter->format_in_bdt($row->credit) . '</span>')
                ->editColumn('running_balance', fn ($row) => '<span class="running_balance" data-value="' . $row->running_balance . '">' . $this->converter->format_in_bdt($row->running_balance) . '</span>')
                ->rawColumns(['date', 'particulars', 'voucher_no', 'debit', 'credit', 'running_balance'])
                ->make(true);
        }

        $account = Account::with(['bank', 'cash_flows'])->where('id', $accountId)->first();
        return view('accounting.accounts.account_book', compact('account'));
    }

    // Store bank
    public function store(Request $request)
    {
        //return $request->branch_ids;
        $this->validate($request, [
            'name' => 'required',
            'account_type' => 'required',
        ]);

        if ($request->account_type == 2) {
            $this->validate($request, [
                'bank_id' => 'required',
                'account_number' => 'required',
                "business_location"    => "required|array",
                "business_location.*"  => "required",
            ]);
        }

        $openingBalance = $request->opening_balance ? $request->opening_balance : 0;

        $addAccountGetId = Account::insertGetId([
            'name' => $request->name,
            'account_number' => $request->account_type == 2 ? $request->account_number : null,
            'bank_id' => $request->account_type == 2 ? $request->bank_id : null,
            'account_type' => $request->account_type,
            'opening_balance' => $openingBalance,
            'balance' => $openingBalance,
            $this->accountUtil->accountBalanceType($request->account_type) => $openingBalance,
            'remark' => $request->remark,
            'admin_id' => auth()->user()->id,
        ]);

        if ($request->account_type == 2) {
            foreach ($request->business_location as $branch_id) {
                $addAccountBranch = AccountBranch::insert(
                    [
                        'branch_id' => $branch_id != 'NULL' ? $branch_id : NULL,
                        'account_id' => $addAccountGetId,
                    ]
                );
            }
        } else {
            $addAccountBranch = AccountBranch::insert(
                [
                    'branch_id' => auth()->user()->branch_id,
                    'account_id' => $addAccountGetId,
                ]
            );
        }

        // Add Opening Stock Ledger
        $accountLedger = new AccountLedger();
        $accountLedger->account_id = $addAccountGetId;
        $accountLedger->voucher_type = 0;
        $accountLedger->date = date('Y-m-d H:i:s');
        $accountLedger->{$this->accountUtil->accountBalanceType($request->account_type)} = $openingBalance;
        $accountLedger->amount_type = $this->accountUtil->accountBalanceType($request->account_type);
        $accountLedger->running_balance = $openingBalance;
        $accountLedger->save();

        return response()->json('Account created successfully');
    }

    public function edit($id)
    {
        $account = Account::with('accountBranches')->where('id', $id)->first();
        $isExistsHeadOffice = DB::table('account_branches')->where('account_id', $id)->where('branch_id', NULL)->first();
        $banks = DB::table('banks')->get();
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('accounting.accounts.ajax_view.edit_account', compact('account', 'isExistsHeadOffice', 'banks', 'branches'));
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
                "business_location"    => "required|array",
                "business_location.*"  => "required",
            ]);
        }

        $updateAccount = Account::with('accountBranches')->where('id', $id)->first();

        // update account branches
        if ($updateAccount->account_type == 2) {
            foreach ($updateAccount->accountBranches as $accountBranch) {
                $accountBranch->is_delete_in_update = 1;
                $accountBranch->save();
            }
        }

        $updateAccount->update([
            'name' => $request->name,
            'account_number' => $request->account_type == 2 ? $request->account_number : null,
            'bank_id' => $request->account_type == 2 ? $request->bank_id : null,
            'account_type' => $request->account_type,
            'remark' => $request->remark,
        ]);

        if ($request->account_type == 2) {
            foreach ($request->business_location as $branch) {
                $branch_id = $branch != 'NULL' ? $branch : NULL;
                $accountBranch = AccountBranch::where('account_id', $updateAccount->id)->where('branch_id', $branch_id)->first();
                if ($accountBranch) {

                    $accountBranch->is_delete_in_update = 0;
                    $accountBranch->save();
                } else {

                    $addAccountBranch = AccountBranch::insert(
                        [
                            'branch_id' => $branch_id,
                            'account_id' => $updateAccount->id,
                        ]
                    );
                }
            }
        }

        // Delete unused account branch row
        $accountBranches = AccountBranch::where('account_id', $updateAccount->id)->where('is_delete_in_update', 1)->get();
        foreach ($accountBranches as $accountBranch) {
            $accountBranch->delete();
        }

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

    // public function accountCashflows($accountId)
    // {
    //     $accountCashFlows = CashFlow::with([
    //         'sender_account',
    //         'receiver_account',
    //         'sale_payment',
    //         'sale_payment.customer',
    //         'sale_payment.sale',
    //         'purchase_payment',
    //         'purchase_payment.supplier',
    //         'purchase_payment.purchase',
    //         'expanse_payment',
    //         'expanse_payment.expense',
    //         'money_receipt',
    //         'money_receipt.customer',
    //         'payroll',
    //         'payroll_payment',
    //         'loan',
    //         'loan_payment',
    //         'loan_payment.branch',
    //         'loan_payment.company',
    //         'loan.company',
    //     ])->where('account_id', $accountId)->orderBy('id', 'desc')->get();
    //     return view('accounting.accounts.ajax_view.account_cash_flow_list', compact('accountCashFlows'));
    // }

    // public function accountCashflowFilter(Request $request, $accountId)
    // {
    //     $filterAccountCashFlows = '';
    //     $query = CashFlow::with([
    //         'sender_account',
    //         'receiver_account',
    //         'sale_payment',
    //         'sale_payment.customer',
    //         'sale_payment.sale',
    //         'purchase_payment',
    //         'purchase_payment.supplier',
    //         'purchase_payment.purchase',
    //         'expanse_payment',
    //         'expanse_payment.expense',
    //         'money_receipt',
    //         'money_receipt.customer',
    //         'payroll',
    //         'payroll_payment',
    //         'loan',
    //         'loan_payment',
    //         'loan_payment.branch',
    //         'loan_payment.company',
    //         'loan.company',
    //     ])->where('account_id', $accountId);

    //     if ($request->from_date) {
    //         $fromDate = date('Y-m-d', strtotime($request->from_date));
    //         $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
    //         $date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
    //         $query->whereBetween('report_date', $date_range); // Final
    //     }

    //     if ($request->transaction_type) {
    //         $query->where('cash_type', $request->transaction_type);
    //     }
    //     $filterAccountCashFlows = $query->orderBy('id', 'desc')->get();
    //     return view('accounting.accounts.ajax_view.filter_account_cash_flow_list', compact('filterAccountCashFlows'));
    // }

    // public function deleteCashFlow($cashFlowId)
    // {
    //     $deleteCashflow = CashFlow::with('account', 'sender_account', 'receiver_account')
    //         ->where('id', $cashFlowId)->first();
    //     if (!is_null($deleteCashflow)) {
    //         if ($deleteCashflow->transaction_type == 4) {
    //             if ($deleteCashflow->cash_type == 1) {
    //                 $deleteCashflow->account->debit -= $deleteCashflow->debit;
    //                 $deleteCashflow->account->balance += $deleteCashflow->debit;
    //                 $deleteCashflow->account->save();

    //                 $deleteCashflow->receiver_account->credit -= $deleteCashflow->debit;
    //                 $deleteCashflow->receiver_account->balance -= $deleteCashflow->debit;
    //                 $deleteCashflow->receiver_account->save();
    //             } elseif ($deleteCashflow->cash_type == 2) {
    //                 $deleteCashflow->account->credit -= $deleteCashflow->credit;
    //                 $deleteCashflow->account->balance -= $deleteCashflow->debit;
    //                 $deleteCashflow->account->save();

    //                 $deleteCashflow->sender_account->debit -= $deleteCashflow->credit;
    //                 $deleteCashflow->sender_account->balance += $deleteCashflow->debit;
    //                 $deleteCashflow->sender_account->save();
    //             }
    //         } elseif ($deleteCashflow->transaction_type == 5) {
    //             $deleteCashflow->account->credit -= $deleteCashflow->credit;
    //             $deleteCashflow->account->balance -= $deleteCashflow->debit;
    //             $deleteCashflow->account->save();

    //             if ($deleteCashflow->sender_account) {
    //                 $deleteCashflow->sender_account->debit -= $deleteCashflow->credit;
    //                 $deleteCashflow->sender_account->balance += $deleteCashflow->balance;
    //                 $deleteCashflow->sender_account->save();
    //             }
    //         }
    //         if ($deleteCashflow->related_cash_flow_id) {
    //             $relatedCashFlow = CashFlow::where('id', $deleteCashflow->related_cash_flow_id)->first();
    //             $relatedCashFlow->delete();
    //         }
    //         $deleteCashflow->delete();
    //     }
    //     return response()->json('Successfully cashflow is deleted');
    // }
}
