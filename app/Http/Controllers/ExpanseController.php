<?php

namespace App\Http\Controllers;

use App\Models\Expanse;
use App\Models\ExpanseCategory;
use App\Models\ExpansePayment;
use App\Models\ExpenseDescription;
use App\Models\PaymentMethod;
use App\Utils\AccountUtil;
use App\Utils\ExpenseUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\UserActivityLogUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpanseController extends Controller
{
    protected $expenseUtil;

    protected $accountUtil;

    protected $invoiceVoucherRefIdUtil;

    protected $userActivityLogUtil;

    protected $util;

    public function __construct(
        ExpenseUtil $expenseUtil,
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        UserActivityLogUtil $userActivityLogUtil,
        Util $util
    ) {
        $this->expenseUtil = $expenseUtil;
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->util = $util;

    }

    // Expanse index view
    public function index(Request $request)
    {
        if (! auth()->user()->can('view_expense')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->expenseUtil->expenseListTable($request);
        }

        $ex_cates = DB::table('expanse_categories')->get();

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();

        return view('expanses.index', compact('branches', 'ex_cates'));
    }

    public function categoryWiseExpense(Request $request)
    {
        if (! auth()->user()->can('category_wise_expense')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->expenseUtil->categoryWiseExpenseListTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();

        return view('expanses.category_wise_expense_list', compact('branches'));
    }

    // Create expanse view
    public function create()
    {
        if (! auth()->user()->can('add_expense')) {

            abort(403, 'Access Forbidden.');
        }

        $users = DB::table('users')->where('branch_id', auth()->user()->branch_id)
            ->select('id', 'prefix', 'name', 'last_name')->get();

        $taxes = DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();

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

        $expenseAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->whereIn('accounts.account_type', [7, 8, 9, 10, 15])
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'account_type']);

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        return view('expanses.create', compact('expenseAccounts', 'accounts', 'methods', 'users', 'taxes'));
    }

    // Store Expanse
    public function store(Request $request)
    {
        if (! auth()->user()->can('add_expense')) {

            return response()->json('Access Denied');
        }

        $generalSettings = config('generalSettings');
        $invoicePrefix = $generalSettings['prefix__expenses'];
        $paymentInvoicePrefix = $generalSettings['prefix__expanse_payment'];

        $this->validate($request, [
            'date' => 'required',
            'ex_account_id' => 'required',
            'account_id' => 'required',
            'total_amount' => 'required',
            'paying_amount' => 'required',
        ]);

        // Add expanse
        $addExpanse = new Expanse();
        $addExpanse->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '').str_pad($this->invoiceVoucherRefIdUtil->getLastId('expanses'), 5, '0', STR_PAD_LEFT);
        $addExpanse->expense_account_id = $request->ex_account_id;
        $addExpanse->branch_id = auth()->user()->branch_id;
        $addExpanse->tax_percent = $request->tax ? $request->tax : 0;
        $addExpanse->total_amount = $request->total_amount;
        $addExpanse->net_total_amount = $request->net_total_amount;
        $addExpanse->paid = $request->paying_amount;
        $addExpanse->due = $request->total_due;
        $addExpanse->date = $request->date;
        $addExpanse->report_date = date('Y-m-d', strtotime($request->date));
        $addExpanse->month = date('F');
        $addExpanse->year = date('Y');
        $addExpanse->admin_id = $request->admin_id;
        $category_ids = '';

        foreach ($request->category_ids as $category_id) {

            $category_ids .= $category_id.', ';
        }

        $addExpanse->category_ids = $category_ids;

        if ($request->hasFile('attachment')) {

            $expanseAttachment = $request->file('attachment');
            $expanseAttachmentName = uniqid().'-'.'.'.$expanseAttachment->getClientOriginalExtension();
            $expanseAttachment->move(public_path('uploads/expanse_attachment/'), $expanseAttachmentName);
            $addExpanse->attachment = $expanseAttachmentName;
        }

        $addExpanse->save();

        $this->userActivityLogUtil->addLog(
            action: 1,
            subject_type: 15,
            data_obj: $addExpanse
        );

        $index = 0;
        foreach ($request->category_ids as $category_id) {

            $addExDescription = new ExpenseDescription();
            $addExDescription->expense_id = $addExpanse->id;
            $addExDescription->expense_category_id = $category_id;
            $addExDescription->amount = $request->amounts[$index];
            $addExDescription->save();
            $index++;
        }

        // Add expense account Ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 5,
            date: $request->date,
            account_id: $request->ex_account_id,
            trans_id: $addExpanse->id,
            amount: $request->net_total_amount,
            balance_type: 'debit'
        );

        if ($request->paying_amount > 0) {

            $addPaymentGetId = $this->expenseUtil->addPaymentGetId(
                voucher_prefix: $paymentInvoicePrefix,
                expense_id: $addExpanse->id,
                request: $request
            );

            // Add bank account Ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 9,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $addPaymentGetId,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );
        }

        $expense = Expanse::with(['expense_descriptions', 'expense_descriptions.category', 'admin'])
            ->where('id', $addExpanse->id)->first();

        return view('expanses.ajax_view.expense_print', compact('expense'));
    }

    //Delete Expanse
    public function delete(Request $request, $expanseId)
    {
        if (! auth()->user()->can('delete_expense')) {

            return response()->json('Access Denied');
        }

        $deleteExpense = Expanse::with('expense_payments')->where('id', $expanseId)->first();

        if ($deleteExpense->transfer_branch_to_branch_id) {

            return response()->json(
                'Expense can not be deleted. This expense is belonging a business location to business location transfer.'
            );
        }

        $this->userActivityLogUtil->addLog(action: 3, subject_type: 15, data_obj: $deleteExpense);

        $this->expenseUtil->expenseDelete($deleteExpense);

        DB::statement('ALTER TABLE expanses AUTO_INCREMENT = 1');

        return response()->json('Successfully expanse is deleted');
    }

    // Edit view
    public function edit($expenseId)
    {
        if (! auth()->user()->can('edit_expense')) {
            abort(403, 'Access Forbidden.');
        }

        $expense = Expanse::with('expense_descriptions')->where('id', $expenseId)->first();

        if ($expense->transfer_branch_to_branch_id) {

            session()->flash('errorMsg', 'Can not be edited. Expense is created by Business Location to Business Location Transfer.');

            return redirect()->back();
        }

        $categories = DB::table('expanse_categories')->orderBy('code', 'asc')->get();

        $taxes = DB::table('taxes')->get();

        $users = DB::table('users')
            ->where('branch_id', auth()->user()->branch_id)
            ->get(['id', 'prefix', 'name', 'last_name']);

        $expenseAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->whereIn('account_type', [7, 8, 9, 10, 15])
            ->get(['accounts.id', 'accounts.name', 'account_type']);

        return view('expanses.edit', compact('expense', 'categories', 'users', 'taxes', 'expenseAccounts'));
    }

    // Update expanse
    public function update(Request $request, $expenseId)
    {
        if (! auth()->user()->can('edit_expense')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'date' => 'required',
            'ex_account_id' => 'required',
            'total_amount' => 'required',
        ]);

        // Add expanse
        $updateExpanse = Expanse::where('id', $expenseId)->first();
        $updateExpanse->expense_account_id = $request->ex_account_id;
        $updateExpanse->note = $request->expanse_note;
        $updateExpanse->tax_percent = $request->tax ? $request->tax : 0;
        $updateExpanse->total_amount = $request->total_amount;
        $updateExpanse->net_total_amount = $request->net_total_amount;
        $updateExpanse->date = $request->date;
        $updateExpanse->report_date = date('Y-m-d', strtotime($request->date));
        $updateExpanse->month = date('F');
        $updateExpanse->year = date('Y');
        $updateExpanse->admin_id = $request->admin_id;

        if ($request->hasFile('attachment')) {

            if ($updateExpanse->attachment != null) {

                if (file_exists(public_path('uploads/expanse_attachment/'.$updateExpanse->attachment))) {

                    unlink(public_path('uploads/expanse_attachment/'.$updateExpanse->attachment));
                }
            }

            $expanseAttachment = $request->file('attachment');
            $expanseAttachmentName = uniqid().'-'.'.'.$expanseAttachment->getClientOriginalExtension();
            $expanseAttachment->move(public_path('uploads/expanse_attachment/'), $expanseAttachmentName);
            $updateExpanse->attachment = $expanseAttachmentName;
        }

        $category_ids = '';
        foreach ($request->category_ids as $category_id) {

            $category_ids .= $category_id.', ';
        }

        $updateExpanse->category_ids = $category_ids;

        $updateExpanse->save();

        $adjustedExpense = $this->expenseUtil->adjustExpenseAmount($updateExpanse);

        $this->userActivityLogUtil->addLog(action: 2, subject_type: 15, data_obj: $adjustedExpense);

        $exDescriptions = ExpenseDescription::where('expense_id', $updateExpanse->id)->get();

        foreach ($exDescriptions as $exDescription) {

            $exDescription->is_delete_in_update = 1;
            $exDescription->save();
        }

        $index = 0;
        foreach ($request->category_ids as $category_id) {

            $description = ExpenseDescription::where('id', $request->description_ids[$index])->first();

            if ($description) {

                $description->expense_category_id = $category_id;
                $description->amount = $request->amounts[$index];
                $description->is_delete_in_update = 0;
                $description->save();
            } else {

                $addExDescription = new ExpenseDescription();
                $addExDescription->expense_id = $updateExpanse->id;
                $addExDescription->expense_category_id = $category_id;
                $addExDescription->amount = $request->amounts[$index];
                $addExDescription->save();
            }

            $index++;
        }

        $deleteAbleExDescriptions = ExpenseDescription::where('expense_id', $updateExpanse->id)
            ->where('is_delete_in_update', 1)->get();

        foreach ($deleteAbleExDescriptions as $exDescription) {

            $exDescription->delete();
        }

        // Add expense account Ledger
        $this->accountUtil->updateAccountLedger(
            voucher_type_id: 5,
            date: $request->date,
            account_id: $request->ex_account_id,
            trans_id: $updateExpanse->id,
            amount: $request->net_total_amount,
            balance_type: 'debit'
        );

        return response()->json(['successMsg' => 'Successfully expense is updated']);
    }

    // Get all form Categories by ajax request
    public function allCategories()
    {
        $categories = ExpanseCategory::orderBy('code', 'asc')->get();

        return response()->json($categories);
    }

    // Payment view method
    public function paymentView($expanseId)
    {
        $expense = Expanse::with(['branch',  'expense_payments', 'expense_payments.payment_method'])->where('id', $expanseId)->first();

        return view('expanses.ajax_view.payment_view', compact('expense'));
    }

    // Payment details
    public function paymentDetails($paymentId)
    {
        $payment = ExpansePayment::with(['expense', 'payment_method', 'expense.expense_descriptions', 'expense.expense_descriptions.category', 'expense.admin'])->where('id', $paymentId)->first();

        return view('expanses.ajax_view.payment_details', compact('payment'));
    }

    // Add expense payment modal view
    public function paymentModal($expenseId)
    {
        $expense = Expanse::with('branch')->where('id', $expenseId)->first();

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

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        return view('expanses.ajax_view.add_payment', compact('expense', 'accounts', 'methods'));
    }

    // Expanse payment method
    public function payment(Request $request, $expenseId)
    {
        $generalSettings = config('generalSettings');
        $paymentInvoicePrefix = $generalSettings['prefix__expanse_payment'];
        $expense = Expanse::where('id', $expenseId)->first();

        $addPaymentGetId = $this->expenseUtil->addPaymentGetId(
            voucher_prefix: $paymentInvoicePrefix,
            expense_id: $expense->id,
            request: $request
        );

        $this->expenseUtil->adjustExpenseAmount($expense);

        // Add Bank/Cash-in-hand Account Ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 9,
            date: $request->date,
            account_id: $request->account_id,
            trans_id: $addPaymentGetId,
            amount: $request->paying_amount,
            balance_type: 'debit'
        );

        return response()->json('Successfully payment is added.');
    }

    // Expense Payment edit view
    public function paymentEdit($paymentId)
    {
        $payment = ExpansePayment::with(['expense'])->where('id', $paymentId)->first();

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

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        return view('expanses.ajax_view.edit_payment', compact('payment', 'accounts', 'methods'));
    }

    // Update payment
    public function paymentUpdate(Request $request, $paymentId)
    {
        $updateExpansePayment = ExpansePayment::with('expense')->where('id', $paymentId)->first();

        if ($updateExpansePayment) {

            $this->expenseUtil->updatePayment($updateExpansePayment, $request);

            $expense = Expanse::where('id', $updateExpansePayment->expanse_id)
                ->select('id', 'net_total_amount', 'paid', 'due')->first();

            $this->expenseUtil->adjustExpenseAmount($expense);

            // Update Bank/Cash-In-Hand account Ledger
            $this->accountUtil->updateAccountLedger(
                voucher_type_id: 9,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $updateExpansePayment->id,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );
        }

        return response()->json('Successfully payment is added.');
    }

    //Delete expense payment
    public function paymentDelete(Request $request, $paymentId)
    {
        $deleteExpensePayment = ExpansePayment::where('id', $paymentId)->first();
        $storedAccountId = $deleteExpensePayment->account_id;
        $storedExpenseId = $deleteExpensePayment->expanse_id;

        if (! is_null($deleteExpensePayment)) {
            // Update expanse
            if ($deleteExpensePayment->attachment != null) {

                if (file_exists(public_path('uploads/payment_attachment/'.$deleteExpansePayment->attachment))) {

                    unlink(public_path('uploads/payment_attachment/'.$deleteExpansePayment->attachment));
                }
            }

            $deleteExpensePayment->delete();
        }

        $expense = Expanse::where('id', $storedExpenseId)->first();
        $this->expenseUtil->adjustExpenseAmount($expense);

        if ($storedAccountId) {

            $this->accountUtil->adjustAccountBalance('debit', $storedAccountId);
        }

        DB::statement('ALTER TABLE expanse_payments AUTO_INCREMENT = 1');

        return response()->json('Successfully payment is deleted.');
    }

    public function addQuickExpenseCategory(Request $request)
    {
        return $this->util->addQuickExpenseCategory($request);
    }
}
