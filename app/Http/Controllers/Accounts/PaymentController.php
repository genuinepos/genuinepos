<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Models\Setups\Branch;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Services\Sales\SaleService;
use App\Enums\AccountingVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Services\CodeGenerationService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Accounts\PaymentService;
use App\Services\Sales\SalesReturnService;
use App\Services\Purchases\PurchaseService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\DayBookVoucherService;
use App\Services\Purchases\PurchaseReturnService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private SaleService $saleService,
        private SalesReturnService $salesReturnService,
        private PurchaseService $purchaseService,
        private PurchaseReturnService $purchaseReturnService,
        private BranchService $branchService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private AccountLedgerService $accountLedgerService,
        private PaymentMethodService $paymentMethodService,
        private DayBookService $dayBookService,
        private DayBookVoucherService $dayBookVoucherService,
        private BranchSettingService $branchSettingService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
    ) {
    }

    public function index(Request $request, $debitAccountId = null)
    {
        if ($request->ajax()) {

            return $this->paymentService->paymentsTable(request: $request, debitAccountId: $debitAccountId);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $debitAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('accounting.accounting_vouchers.payments.index', compact('branches', 'debitAccounts'));
    }

    function show($id)
    {
        $payment = $this->accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: [
                'branch',
                'branch.parentBranch',
                'voucherDescriptions',
                'voucherDescriptions.references',
                'voucherDescriptions.references.sale',
                'voucherDescriptions.references.purchaseReturn',
                'voucherDescriptions.references.stockAdjustment',
                'purchaseRef',
                'salesReturnRef',
                'stockAdjustmentRef',
            ],
        );

        return view('accounting.accounting_vouchers.payments.ajax_view.show', compact('payment'));
    }

    public function create($debitAccountId = null)
    {
        $account = '';
        $vouchers = [];
        if (isset($debitAccountId)) {

            $account = $this->accountService->singleAccountById(id: $debitAccountId, with: ['contact']);

            $trans = $this->dayBookVoucherService->vouchersForPaymentReceipt(accountId: $debitAccountId, type: AccountingVoucherType::Payment->value);

            $vouchers = $this->dayBookVoucherService->filteredVoucher(vouchers: $trans);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $receivableAccounts = '';
        if (!isset($creditAccountId)) {

            $receivableAccounts = $this->accountService->branchAccessibleAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId);
        }

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return view('accounting.accounting_vouchers.payments.ajax_view.create', compact('vouchers', 'account', 'accounts', 'methods', 'receivableAccounts'));
    }

    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'paying_amount' => 'required',
            'payment_method_id' => 'required',
            'debit_account_id' => 'required',
            'credit_account_id' => 'required',
        ]);

        // dd($request->ref_ids);

        try {
            DB::beginTransaction();

            $restrictions = $this->paymentService->restrictions(request: $request);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $paymentVoucherPrefix = isset($branchSetting) && $branchSetting?->payment_voucher_prefix ? $branchSetting?->payment_voucher_prefix : $generalSettings['prefix__payment'];

            // Add Accounting Voucher
            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Payment->value, remarks: $request->remarks, reference: $request->reference, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount);

            // Add Payment Description Debit Entry
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

            // Add Day Book entry for Payment
            $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Payment->value, date: $request->date, accountId: $request->debit_account_id, transId: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amountType: 'debit');

             // Add Accounting VoucherDescription References
             $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherDebitDescription->id, accountId: $request->debit_account_id, amount: $request->paying_amount, refIdColName: 'purchase_id', refIds: isset($request->ref_ids) ? $request->ref_ids : null);

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->credit_account_id);


            // Add Credit Account Accounting voucher Description
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');

            $payment = $this->accountingVoucherService->singleAccountingVoucher(
                id: $addAccountingVoucher->id,
                with: [
                    'branch',
                    'branch.parentBranch',
                    'voucherDescriptions',
                    'voucherDescriptions.references',
                    'voucherDescriptions.references.purchase',
                    'voucherDescriptions.references.salesReturn',
                    'purchaseRef',
                    'salesReturnRef',
                ],
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('accounting.accounting_vouchers.save_and_print_template.print_payment', compact('payment'));
        } else {

            return response()->json(['successMsg' => __("Payment added successfully.")]);
        }
    }

    function edit($id, $debitAccountId = null)
    {
        $payment = $this->accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: [
                'voucherCreditDescription',
                'voucherDebitDescription',
                'voucherDebitDescription.references',
                'voucherDebitDescription.references.purchase',
                'voucherDebitDescription.references.salesReturn',
            ],
        );

        $account = '';
        $vouchers = [];
        if (isset($debitAccountId)) {

            $account = $this->accountService->singleAccountById(id: $debitAccountId, with: ['contact']);
        }

        $transAccountId = isset($debitAccountId) ? $debitAccountId : $payment->voucherDebitDescription->account_id;
        $trans = $this->dayBookVoucherService->vouchersForPaymentReceipt(accountId: $transAccountId, type: AccountingVoucherType::Payment->value);
        $vouchers = $this->dayBookVoucherService->filteredVoucher(vouchers: $trans);

        $ownBranchIdOrParentBranchId = $payment?->branch?->parent_branch_id ? $payment?->branch?->parent_branch_id : $payment?->branch_id;

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $payableAccounts = '';
        if (!isset($debitAccountId)) {

            $payableAccounts = $this->accountService->branchAccessibleAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId);
        }

        return view('accounting.accounting_vouchers.payments.ajax_view.edit', compact('payment', 'vouchers', 'account', 'accounts', 'methods', 'payableAccounts'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'paying_amount' => 'required',
            'payment_method_id' => 'required',
            'debit_account_id' => 'required',
            'credit_account_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $restrictions = $this->paymentService->restrictions(request: $request);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            // Add Accounting Voucher
            $updateAccountingVoucher = $this->accountingVoucherService->updateAccountingVoucher(id: $id, date: $request->date, remarks: $request->remarks, reference: $request->reference, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount);

             // Add Payment Description Debit Entry
             $updateAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherDebitDescription->id, accountId: $request->debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

             // Add Day Book entry for Payment
             $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Payment->value, date: $request->date, accountId: $request->debit_account_id, transId: $updateAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amountType: 'debit');

             if ($updateAccountingVoucher?->voucherDebitDescription && count($updateAccountingVoucher?->voucherDebitDescription?->references) > 0) {

                 foreach ($updateAccountingVoucher?->voucherDebitDescription?->references as $reference) {

                     $reference->delete();

                     if ($reference->purchase) {

                         $this->purchaseService->adjustPurchaseInvoiceAmounts(purchase: $reference->purchase);
                     } else if ($reference->salesReturn) {

                         $this->salesReturnService->adjustSalesReturnVoucherAmounts(salesReturn: $reference->salesReturn);
                     }
                 }
             }

             // Add Accounting VoucherDescription References
             $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $updateAccountingVoucherDebitDescription->id, accountId: $request->debit_account_id, amount: $request->paying_amount, refIdColName: 'purchase_id', refIds: isset($request->ref_ids) ? $request->ref_ids : null);

             //Add Debit Ledger Entry
             $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $updateAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherDebitDescription->current_account_id, cash_bank_account_id: $request->credit_account_id);

            // Add Credit Account Accounting voucher Description
            $updateAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherCreditDescription->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

            //Add credit Ledger Entry
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $updateAccountingVoucherCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherCreditDescription->current_account_id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Payment updated successfully."));
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $deletePayment = $this->paymentService->deletePayment(id: $id);

            foreach ($deletePayment->voucherDescriptions as $voucherDescription) {

                if (count($voucherDescription->references)) {

                    foreach ($voucherDescription->references as $reference) {

                        if ($reference->sale) {

                            $this->saleService->adjustSaleInvoiceAmounts(sale: $reference->sale);
                        } else if ($reference->purchase) {

                            $this->purchaseService->adjustPurchaseInvoiceAmounts(purchase: $reference->purchase);
                        } else if ($reference->salesReturn) {

                            $this->salesReturnService->adjustSalesReturnVoucherAmounts(salesReturn: $reference->salesReturn);
                        } else if ($reference->purchaseReturn) {

                            $this->purchaseReturnService->adjustPurchaseReturnVoucherAmounts(purchaseReturn: $reference->purchaseReturn);
                        }
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Payment deleted successfully."));
    }
}
