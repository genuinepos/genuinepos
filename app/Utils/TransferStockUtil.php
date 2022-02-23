<?php

namespace App\Utils;

use App\Models\Expanse;
use App\Models\ExpanseCategory;
use App\Models\ExpenseDescription;
use Illuminate\Support\Facades\DB;

class TransferStockUtil
{
    public $accountUtil;
    public $invoiceVoucherRefIdUtil;
    public $expenseUtil;

    public function __construct(
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        ExpenseUtil $expenseUtil
    ) {
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->expenseUtil = $expenseUtil;
    }

    public function addExpenseFromTransferStock($request, $transfer_id)
    {
        $settings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($settings->prefix, true)['expenses'];
        $paymentInvoicePrefix = json_decode($settings->prefix, true)['expanse_payment'];

        $transferCostCategory = DB::table('expanse_categories')->where('name', 'Transferring Cost')->first();

        $expense_category_id;
        if (!$transferCostCategory) {

            $addGetId = ExpanseCategory::insertGetId([
                'name' => 'Transferring Cost',
                'code' => $this->invoiceVoucherRefIdUtil->getLastId('expanse_categories'),
            ]);

            $expense_category_id = $addGetId;
        }

        $__expense_category_id = $transferCostCategory ? $transferCostCategory->id : $expense_category_id;

        $voucher_no = str_pad($this->invoiceVoucherRefIdUtil->getLastId('expanses'), 5, "0", STR_PAD_LEFT);

        $addExpense = new Expanse();
        $addExpense->invoice_id = ($invoicePrefix != null ? $invoicePrefix : '') . $voucher_no;
        $addExpense->branch_id = auth()->user()->branch_id;
        $addExpense->category_ids = $__expense_category_id;
        $addExpense->total_amount = $request->transfer_cost;
        $addExpense->net_total_amount = $request->transfer_cost;
        $addExpense->paid = $request->transfer_cost;
        $addExpense->date = $request->date;
        $addExpense->month = date('F');
        $addExpense->year = date('Y');
        $addExpense->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        //$addExpense->admin_id = auth()->user()->id;
        $addExpense->expense_account_id = $request->ex_account_id;
        $addExpense->transfer_branch_to_branch_id = $transfer_id;
        $addExpense->save();

        // Add expanse Description
        $addExDescription = new ExpenseDescription();
        $addExDescription->expense_id = $addExpense->id;
        $addExDescription->expense_category_id = $__expense_category_id;
        $addExDescription->amount = $request->transfer_cost;
        $addExDescription->save();

        // Add expense account Ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 5,
            date: $request->date,
            account_id: $request->ex_account_id,
            trans_id: $addExpense->id,
            amount: $request->transfer_cost,
            balance_type: 'debit'
        );

        // Add Expanse Payment
        $addPaymentGetId = $this->expenseUtil->addPaymentGetId(
            voucher_prefix: $paymentInvoicePrefix,
            expense_id: $addExpense->id,
            request: $request,
            another_amount: $request->transfer_cost,
        );

        // Add bank account Ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 9,
            date: $request->date,
            account_id: $request->account_id,
            trans_id: $addPaymentGetId,
            amount: $request->transfer_cost,
            balance_type: 'debit'
        );
    }
}
