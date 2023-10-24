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
use App\Services\Accounts\ReceiptService;
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

class ReceiptController extends Controller
{
    public function __construct(
        private ReceiptService $receiptService,
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

    public function index(Request $request, $creditAccountId = null)
    {
        if ($request->ajax()) {

            return $this->receiptService->receiptsTable(request: $request, creditAccountId: $creditAccountId);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $creditAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('accounting.accounting_vouchers.receipts.index', compact('branches', 'creditAccounts'));
    }

    function show($id)
    {
        $receipt = $this->accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: [
                'branch',
                'branch.parentBranch',
                'voucherDescriptions',
                'voucherDescriptions.references',
                'voucherDescriptions.references.sale',
                'voucherDescriptions.references.purchaseReturn',
                'voucherDescriptions.references.stockAdjustment',
                'saleRef',
                'purchaseReturnRef',
                'stockAdjustmentRef',
            ],
        );

        return view('accounting.accounting_vouchers.receipts.ajax_view.show', compact('receipt'));
    }

    public function create($creditAccountId = null)
    {
        $account = '';
        $vouchers = [];
        if (isset($creditAccountId)) {

            $account = $this->accountService->singleAccountById(id: $creditAccountId, with: ['contact']);

            $trans = $this->dayBookVoucherService->vouchersForPaymentReceipt(accountId: $creditAccountId, type: AccountingVoucherType::Receipt->value);

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

        return view('accounting.accounting_vouchers.receipts.ajax_view.create', compact('vouchers', 'account', 'accounts', 'methods', 'receivableAccounts'));
    }

    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'received_amount' => 'required',
            'payment_method_id' => 'required',
            'debit_account_id' => 'required',
            'credit_account_id' => 'required',
        ]);

        // dd($request->ref_ids);

        try {
            DB::beginTransaction();

            $restrictions = $this->receiptService->restrictions(request: $request);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt'];

            // Add Accounting Voucher
            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->remarks, reference: $request->reference, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->debit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->credit_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount);

            // Add Day Book entry for Receipt
            $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Receipt->value, date: $request->date, accountId: $request->credit_account_id, transId: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amountType: 'credit');

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->credit_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: isset($request->ref_ids) ? $request->ref_ids : null);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->debit_account_id);

            $receipt = $this->accountingVoucherService->singleAccountingVoucher(
                id: $addAccountingVoucher->id,
                with: [
                    'branch',
                    'branch.parentBranch',
                    'voucherDescriptions',
                    'voucherDescriptions.references',
                    'voucherDescriptions.references.sale',
                    'voucherDescriptions.references.purchaseReturn',
                    'voucherDescriptions.references.stockAdjustment',
                    'saleRef',
                    'purchaseReturnRef',
                    'stockAdjustmentRef',
                ],
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('accounting.accounting_vouchers.save_and_print_template.print_receipt', compact('receipt'));
        } else {

            return response()->json(['successMsg' => __("Receipt added successfully.")]);
        }
    }

    function edit($id, $creditAccountId = null)
    {
        $receipt = $this->accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: [
                'voucherDebitDescription',
                'voucherCreditDescription.references',
                'voucherCreditDescription.references.sale',
                'voucherCreditDescription.references.purchaseReturn',
            ],
        );

        $account = '';
        $vouchers = [];
        if (isset($creditAccountId)) {

            $account = $this->accountService->singleAccountById(id: $creditAccountId, with: ['contact']);
        }

        $transAccountId = isset($creditAccountId) ? $creditAccountId : $receipt->voucherCreditDescription->account_id;
        $trans = $this->dayBookVoucherService->vouchersForPaymentReceipt(accountId: $transAccountId, type: AccountingVoucherType::Receipt->value);
        $vouchers = $this->dayBookVoucherService->filteredVoucher(vouchers: $trans);

        $ownBranchIdOrParentBranchId = $receipt?->branch?->parent_branch_id ? $receipt?->branch?->parent_branch_id : $receipt?->branch_id;

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

        $receivableAccounts = '';
        if (!isset($creditAccountId)) {

            $receivableAccounts = $this->accountService->branchAccessibleAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId);
        }

        return view('accounting.accounting_vouchers.receipts.ajax_view.edit', compact('receipt', 'vouchers', 'account', 'accounts', 'methods', 'receivableAccounts'));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'date' => 'required|date',
            'received_amount' => 'required',
            'payment_method_id' => 'required',
            'debit_account_id' => 'required',
            'credit_account_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $restrictions = $this->receiptService->restrictions(request: $request);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            // Add Accounting Voucher
            $updateAccountingVoucher = $this->accountingVoucherService->updateAccountingVoucher(id: $id, date: $request->date, remarks: $request->remarks, reference: $request->reference, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount);

            // Add Debit Account Accounting voucher Description
            $updateAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherDebitDescription->id, accountId: $request->debit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

            //Add Debit Ledger Entry
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $updateAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherDebitDescription->current_account_id);

            // Add Payment Description Credit Entry
            $updateAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherCreditDescription->id, accountId: $request->credit_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount);

            if ($updateAccountingVoucher?->voucherCreditDescription && count($updateAccountingVoucher?->voucherCreditDescription?->references) > 0) {

                foreach ($updateAccountingVoucher?->voucherCreditDescription?->references as $reference) {

                    $reference->delete();

                    if ($reference->sale) {

                        $this->saleService->adjustSaleInvoiceAmounts(sale: $reference->sale);
                    } else if ($reference->purchaseReturn) {

                        $this->purchaseReturnService->adjustPurchaseReturnVoucherAmounts(purchaseReturn: $reference->purchaseReturn);
                    }
                }
            }

            // Add Day Book entry for Receipt
            $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Receipt->value, date: $request->date, accountId: $request->credit_account_id, transId: $updateAccountingVoucherCreditDescription->id, amount: $request->received_amount, amountType: 'credit');

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $updateAccountingVoucherCreditDescription->id, accountId: $request->credit_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: isset($request->ref_ids) ? $request->ref_ids : null);

            //Add Credit Ledger Entry
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $updateAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherCreditDescription->current_account_id, cash_bank_account_id: $request->debit_account_id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Receipt updated successfully."));
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $deleteReceipt = $this->receiptService->deleteReceipt(id: $id);

            foreach ($deleteReceipt->voucherDescriptions as $voucherDescription) {

                if (count($voucherDescription->references) ) {

                    foreach ($voucherDescription->references as $reference) {

                        if ($reference->sale) {

                            $this->saleService->adjustSaleInvoiceAmounts(sale: $reference->sale);
                        }else if($reference->purchase) {

                            $this->purchaseService->adjustPurchaseInvoiceAmounts(purchase: $reference->purchase);
                        }else if($reference->salesReturn) {

                            $this->salesReturnService->adjustSalesReturnVoucherAmounts(salesReturn: $reference->salesReturn);
                        }else if($reference->purchaseReturn) {

                            $this->purchaseReturnService->adjustPurchaseReturnVoucherAmounts(purchaseReturn: $reference->purchaseReturn);
                        }
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Receipt deleted successfully."));
    }
}
