<?php

namespace App\Services\Accounts\MethodContainerServices;

use App\Enums\DayBookVoucherType;
use App\Services\Sales\SaleService;
use App\Enums\AccountingVoucherType;
use App\Services\Branches\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Accounts\PaymentService;
use App\Services\Sales\SalesReturnService;
use App\Services\Purchases\PurchaseService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\DayBookVoucherService;
use App\Services\Purchases\PurchaseReturnService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Interfaces\Accounts\PaymentControllerMethodContainersInterface;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class PaymentControllerMethodContainersService implements PaymentControllerMethodContainersInterface
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
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
    ) {}

    public function indexMethodContainer(object $request, ?int $debitAccountId = null): object|array
    {
        $data = [];
        if ($request->ajax()) {

            return $this->paymentService->paymentsTable(request: $request, debitAccountId: $debitAccountId);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $data['debitAccounts'] = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return $data;
    }

    public function showMethodContainer(int $id): ?array
    {
        $data = [];
        $data['payment'] = $this->accountingVoucherService->singleAccountingVoucher(
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

        return $data;
    }

    public function printMethodContainer(int $id, object $request): ?array
    {
        $data = [];
        $data['payment'] = $this->accountingVoucherService->singleAccountingVoucher(
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

        $data['printPageSize'] = $request->print_page_size;

        return $data;
    }

    public function createMethodContainer(int $debitAccountId = null): ?array
    {
        $data = [];
        $data['account'] = '';
        $data['vouchers'] = [];
        if (isset($debitAccountId)) {

            $data['account'] = $this->accountService->singleAccountById(id: $debitAccountId, with: ['contact']);

            $trans = $this->dayBookVoucherService->vouchersForPaymentReceipt(accountId: $debitAccountId, type: AccountingVoucherType::Payment->value);

            $data['vouchers'] = $this->dayBookVoucherService->filteredVoucher(vouchers: $trans);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,name,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['payableAccounts'] = '';
        if (!isset($creditAccountId)) {

            $data['payableAccounts'] = $this->accountService->branchAccessibleAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId);
        }

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): ?array
    {
        $restrictions = $this->paymentService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $paymentVoucherPrefix = $generalSettings['prefix__payment_voucher_prefix'] ? $generalSettings['prefix__payment_voucher_prefix'] : 'PV';

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

        $printPageSize = $request->print_page_size;

        return ['payment' => $payment, 'printPageSize' => $printPageSize];
    }

    public function editMethodContainer(int $id, int $debitAccountId = null): ?array
    {
        $data = [];
        $payment = $this->accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: [
                'voucherCreditDescription',
                'voucherDebitDescription',
                'voucherDebitDescription.account',
                'voucherDebitDescription.account.group',
                'voucherDebitDescription.references',
                'voucherDebitDescription.references.purchase',
                'voucherDebitDescription.references.salesReturn',
            ],
        );

        $data['account'] = '';
        $data['vouchers'] = [];
        if (isset($debitAccountId)) {

            $data['account'] = $this->accountService->singleAccountById(id: $debitAccountId, with: ['contact']);
        }

        $transAccountId = isset($debitAccountId) ? $debitAccountId : $payment->voucherDebitDescription->account_id;
        $trans = $this->dayBookVoucherService->vouchersForPaymentReceipt(accountId: $transAccountId, type: AccountingVoucherType::Payment->value);
        $data['vouchers'] = $this->dayBookVoucherService->filteredVoucher(vouchers: $trans);

        $ownBranchIdOrParentBranchId = $payment?->branch?->parent_branch_id ? $payment?->branch?->parent_branch_id : $payment?->branch_id;

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,name,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $payment->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['payableAccounts'] = '';
        if (!isset($debitAccountId)) {

            $data['payableAccounts'] = $this->accountService->branchAccessibleAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId);
        }

        $data['payment'] = $payment;

        return $data;
    }

    public function updateMethodContainer(int $id, object $request): ?array
    {
        $restrictions = $this->paymentService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
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
                } elseif ($reference->salesReturn) {

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

        return null;
    }

    public function deleteMethodContainer(int $id): ?object
    {
        $deletePayment = $this->paymentService->deletePayment(id: $id);

        foreach ($deletePayment->voucherDescriptions as $voucherDescription) {

            if (count($voucherDescription->references)) {

                foreach ($voucherDescription->references as $reference) {

                    if ($reference->sale) {

                        $this->saleService->adjustSaleInvoiceAmounts(sale: $reference->sale);
                    } elseif ($reference->purchase) {

                        $this->purchaseService->adjustPurchaseInvoiceAmounts(purchase: $reference->purchase);
                    } elseif ($reference->salesReturn) {

                        $this->salesReturnService->adjustSalesReturnVoucherAmounts(salesReturn: $reference->salesReturn);
                    } elseif ($reference->purchaseReturn) {

                        $this->purchaseReturnService->adjustPurchaseReturnVoucherAmounts(purchaseReturn: $reference->purchaseReturn);
                    }
                }
            }
        }

        return $deletePayment;
    }
}
