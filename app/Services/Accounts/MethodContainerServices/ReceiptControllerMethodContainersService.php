<?php

namespace App\Services\Accounts\MethodContainerServices;

use App\Enums\DayBookVoucherType;
use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Interfaces\Accounts\ReceiptControllerMethodContainersInterface;

class ReceiptControllerMethodContainersService implements ReceiptControllerMethodContainersInterface
{
    public function showMethodContainer(
        int $id = null,
        object $accountingVoucherService,
    ): ?array {

        $data = [];
        $data['receipt'] = $accountingVoucherService->singleAccountingVoucher(
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

        return $data;
    }

    public function createMethodContainer(
        ?int $creditAccountId = null,
        object $accountService,
        object $accountFilterService,
        object $dayBookVoucherService,
        object $paymentMethodService,
    ): ?array {

        $data = [];
        $data['account'] = '';
        $data['vouchers'] = [];
        if (isset($creditAccountId)) {

            $data['account'] = $accountService->singleAccountById(id: $creditAccountId, with: ['contact']);

            $trans = $dayBookVoucherService->vouchersForPaymentReceipt(accountId: $creditAccountId, type: AccountingVoucherType::Receipt->value);

            $data['vouchers'] = $dayBookVoucherService->filteredVoucher(vouchers: $trans);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $accounts = $accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $accountFilterService->filterCashBankAccounts($accounts);

        $data['receivableAccounts'] = '';
        if (!isset($creditAccountId)) {

            $data['receivableAccounts'] = $accountService->branchAccessibleAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId);
        }

        $data['methods'] = $paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return $data;
    }

    public function storeMethodContainer(
        object $request,
        object $receiptService,
        object $branchSettingService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $dayBookService,
        object $codeGenerator,
    ): ?array {

        $restrictions = $receiptService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $branchSetting = $branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
        $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt'];

        // Add Accounting Voucher
        $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->remarks, reference: $request->reference, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount);

        // Add Debit Account Accounting voucher Description
        $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->debit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

        //Add Debit Ledger Entry
        $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

        // Add Payment Description Credit Entry
        $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->credit_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount);

        // Add Day Book entry for Receipt
        $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Receipt->value, date: $request->date, accountId: $request->credit_account_id, transId: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amountType: 'credit');

        // Add Accounting VoucherDescription References
        $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->credit_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: isset($request->ref_ids) ? $request->ref_ids : null);

        //Add Credit Ledger Entry
        $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->debit_account_id);

        $receipt = $accountingVoucherService->singleAccountingVoucher(
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

        return ['receipt' => $receipt];
    }

    public function editMethodContainer(
        int $id,
        ?int $creditAccountId = null,
        object $accountingVoucherService,
        object $accountService,
        object $accountFilterService,
        object $dayBookVoucherService,
        object $paymentMethodService,
    ): ?array {

        $data = [];
        $receipt = $accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: [
                'voucherDebitDescription',
                'voucherCreditDescription.references',
                'voucherCreditDescription.references.sale',
                'voucherCreditDescription.references.purchaseReturn',
            ],
        );

        $data['account'] = '';
        $data['vouchers'] = [];
        if (isset($creditAccountId)) {

            $data['account'] = $accountService->singleAccountById(id: $creditAccountId, with: ['contact']);
        }

        $transAccountId = isset($creditAccountId) ? $creditAccountId : $receipt->voucherCreditDescription->account_id;
        $trans = $dayBookVoucherService->vouchersForPaymentReceipt(accountId: $transAccountId, type: AccountingVoucherType::Receipt->value);
        $data['vouchers'] = $dayBookVoucherService->filteredVoucher(vouchers: $trans);

        $ownBranchIdOrParentBranchId = $receipt?->branch?->parent_branch_id ? $receipt?->branch?->parent_branch_id : $receipt?->branch_id;

        $accounts = $accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['receivableAccounts'] = '';
        if (!isset($creditAccountId)) {

            $data['receivableAccounts'] = $accountService->branchAccessibleAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId);
        }

        $data['receipt'] = $receipt;

        return $data;
    }

    public function updateMethodContainer(
        int $id,
        object $request,
        object $receiptService,
        object $branchSettingService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $dayBookService,
        object $saleService,
        object $purchaseReturnService,
    ): ?array {

        $restrictions = $receiptService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        // Add Accounting Voucher
        $updateAccountingVoucher = $accountingVoucherService->updateAccountingVoucher(id: $id, date: $request->date, remarks: $request->remarks, reference: $request->reference, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount);

        // Add Debit Account Accounting voucher Description
        $updateAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherDebitDescription->id, accountId: $request->debit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

        //Add Debit Ledger Entry
        $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $updateAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherDebitDescription->current_account_id);

        // Add Payment Description Credit Entry
        $updateAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherCreditDescription->id, accountId: $request->credit_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount);

        if ($updateAccountingVoucher?->voucherCreditDescription && count($updateAccountingVoucher?->voucherCreditDescription?->references) > 0) {

            foreach ($updateAccountingVoucher?->voucherCreditDescription?->references as $reference) {

                $reference->delete();

                if ($reference->sale) {

                    $saleService->adjustSaleInvoiceAmounts(sale: $reference->sale);
                } else if ($reference->purchaseReturn) {

                    $purchaseReturnService->adjustPurchaseReturnVoucherAmounts(purchaseReturn: $reference->purchaseReturn);
                }
            }
        }

        // Add Day Book entry for Receipt
        $dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Receipt->value, date: $request->date, accountId: $request->credit_account_id, transId: $updateAccountingVoucherCreditDescription->id, amount: $request->received_amount, amountType: 'credit');

        // Add Accounting VoucherDescription References
        $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $updateAccountingVoucherCreditDescription->id, accountId: $request->credit_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: isset($request->ref_ids) ? $request->ref_ids : null);

        //Add Credit Ledger Entry
        $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $updateAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherCreditDescription->current_account_id, cash_bank_account_id: $request->debit_account_id);

        return null;
    }

    public function deleteMethodContainer(
        int $id,
        object $receiptService,
        object $saleService,
        object $salesReturnService,
        object $purchaseService,
        object $purchaseReturnService,
    ): ?object {

        $deleteReceipt = $receiptService->deleteReceipt(id: $id);

        foreach ($deleteReceipt->voucherDescriptions as $voucherDescription) {

            if (count($voucherDescription->references)) {

                foreach ($voucherDescription->references as $reference) {

                    if ($reference->sale) {

                        $saleService->adjustSaleInvoiceAmounts(sale: $reference->sale);
                    } else if ($reference->purchase) {

                        $purchaseService->adjustPurchaseInvoiceAmounts(purchase: $reference->purchase);
                    } else if ($reference->salesReturn) {

                        $salesReturnService->adjustSalesReturnVoucherAmounts(salesReturn: $reference->salesReturn);
                    } else if ($reference->purchaseReturn) {

                        $purchaseReturnService->adjustPurchaseReturnVoucherAmounts(purchaseReturn: $reference->purchaseReturn);
                    }
                }
            }
        }

        return $deleteReceipt;
    }
}
