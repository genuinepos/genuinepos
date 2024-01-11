<?php

namespace App\Services\StockAdjustments\MethodContainerServices;

use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\DayBookVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Interfaces\StockAdjustments\StockAdjustmentControllerMethodContainersInterface;

class StockAdjustmentControllerMethodContainersService implements StockAdjustmentControllerMethodContainersInterface
{
    public function createMethodContainer(
        object $branchService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
        object $warehouseService,
    ): array {

        $data = [];
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $data['branchName'] = $branchService->branchName();

        $accounts = $accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

        $data['accounts'] = $accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['expenseAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 10)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['warehouses'] = $warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        return $data;
    }

    public function storeMethodContainer(
        object $request,
        object $branchSettingService,
        object $stockAdjustmentService,
        object $stockAdjustmentProductService,
        object $dayBookService,
        object $accountLedgerService,
        object $productStockService,
        object $productLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $userActivityLogUtil,
        object $codeGenerator
    ): ?array {

        $restrictions = $stockAdjustmentService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $branchSetting = $branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
        $voucherPrefix = isset($branchSetting) && $branchSetting?->stock_adjustment_prefix ? $branchSetting?->stock_adjustment_prefix : $generalSettings['prefix__stock_adjustment_prefix'];
        $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt_voucher_prefix'];

        $addStockAdjustment = $stockAdjustmentService->addStockAdjustment(request: $request, codeGenerator: $codeGenerator, voucherPrefix: $voucherPrefix);

        // Add Day Book Entry
        $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::StockAdjustment->value, date: $request->date, accountId: $request->expense_account_id, transId: $addStockAdjustment->id, amount: $request->net_total_amount, amountType: 'debit');

        // Add Expense Ledger Entry
        $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::StockAdjustment->value, account_id: $request->expense_account_id, date: $request->date, trans_id: $addStockAdjustment->id, amount: $request->net_total_amount, amount_type: 'debit');

        $index = 0;
        foreach ($request->product_ids as $product_id) {

            $addStockAdjustmentProduct = $stockAdjustmentProductService->addStockAdjustmentProduct(request: $request, stockAdjustmentId: $addStockAdjustment->id, index: $index);

            $productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::StockAdjustment->value, date: $request->date, productId: $addStockAdjustmentProduct->product_id, transId: $addStockAdjustmentProduct->id, rate: $addStockAdjustmentProduct->unit_cost_inc_tax, quantityType: 'out', quantity: $addStockAdjustmentProduct->quantity, subtotal: $addStockAdjustmentProduct->subtotal, variantId: $addStockAdjustmentProduct->variant_id, warehouseId: $addStockAdjustmentProduct->warehouse_id);

            $productStockService->adjustMainProductAndVariantStock(productId: $addStockAdjustmentProduct->product_id, variantId: $addStockAdjustmentProduct->variant_id);

            $productStockService->adjustBranchAllStock(productId: $addStockAdjustmentProduct->product_id, variantId: $addStockAdjustmentProduct->variant_id, branchId: auth()->user()->branch_id);

            if ($addStockAdjustmentProduct->warehouse_id) {

                $productStockService->adjustWarehouseStock(productId: $addStockAdjustmentProduct->product_id, variantId: $addStockAdjustmentProduct->variant_id, warehouseId: $addStockAdjustmentProduct->warehouse_id);
            } else {

                $productStockService->adjustBranchStock(productId: $addStockAdjustmentProduct->product_id, variantId: $addStockAdjustmentProduct->variant_id, branchId: auth()->user()->branch_id);
            }

            $index++;
        }

        if ($request->recovered_amount > 0) {

            $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: null, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->recovered_amount, creditTotal: $request->recovered_amount, totalAmount: $request->recovered_amount, stockAdjustmentRefId: $addStockAdjustment->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->recovered_amount);

            //Add Debit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->recovered_amount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->expense_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->recovered_amount, note: $request->payment_note);

            // Add Accounting VoucherDescription References
            $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: null, amount: $request->recovered_amount, refIdColName: 'stock_adjustment_id', refIds: [$addStockAdjustment->id]);

            //Add Credit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->expense_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->recovered_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        $userActivityLogUtil->addLog(action: 1, subject_type: 13, data_obj: $addStockAdjustment);

        return null;
    }

    public function deleteMethodContainer(
        int $id,
        object $stockAdjustmentService,
        object $productStockService,
        object $userActivityLogUtil,
    ): ?array {

        $deleteAdjustment = $stockAdjustmentService->deleteStockAdjustment(id: $id);

        if (isset($deleteAdjustment['pass']) && $deleteAdjustment['pass'] == false) {

            return ['pass' => false, 'msg' => $deleteAdjustment['msg']];
        }

        foreach ($deleteAdjustment->adjustmentProducts as $adjustmentProduct) {

            $productStockService->adjustMainProductAndVariantStock($adjustmentProduct->product_id, $adjustmentProduct->variant_id);

            if ($adjustmentProduct->warehouse_id) {

                $productStockService->adjustWarehouseStock($adjustmentProduct->product_id, $adjustmentProduct->variant_id, $adjustmentProduct->warehouse_id);
            } else {

                $productStockService->adjustBranchStock($adjustmentProduct->product_id, $adjustmentProduct->variant_id, $deleteAdjustment->branch_id);
            }
        }

        $userActivityLogUtil->addLog(action: 3, subject_type: 13, data_obj: $deleteAdjustment);

        return null;
    }

    public function showMethodContainer(int $id, object $stockAdjustmentService): ?array
    {
        $data = [];
        $data['adjustment'] = $stockAdjustmentService->singleStockAdjustment(id: $id, with: [
            'branch:id,name,branch_code,area_name,parent_branch_id',
            'branch.parentBranch:id,name,branch_code,area_name',
            'adjustmentProducts',
            'adjustmentProducts.product',
            'adjustmentProducts.variant',
            'adjustmentProducts.branch:id,name,branch_code,area_name,parent_branch_id',
            'adjustmentProducts.branch.parentBranch:id,name,branch_code,area_name',
            'adjustmentProducts.warehouse:id,warehouse_name,warehouse_code',
            'adjustmentProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'adjustmentProducts.unit.baseUnit:id,base_unit_id,code_name',
            'createdBy:id,prefix,name,last_name',

            'references:id,voucher_description_id,stock_adjustment_id,amount',
            'references.voucherDescription:id,accounting_voucher_id',
            'references.voucherDescription.accountingVoucher:id,voucher_no,date,voucher_type',
            'references.voucherDescription.accountingVoucher.voucherDescriptions:id,accounting_voucher_id,account_id,payment_method_id',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.paymentMethod:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.bank:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.group:id,sub_sub_group_number',
        ]);

        return $data;
    }

    public function printMethodContainer(int $id, object $request, object $stockAdjustmentService): ?array
    {
        $data = [];
        $data['adjustment'] = $stockAdjustmentService->singleStockAdjustment(id: $id, with: [
            'branch:id,name,branch_code,area_name,parent_branch_id',
            'branch.parentBranch:id,name,branch_code,area_name',
            'adjustmentProducts',
            'adjustmentProducts.product',
            'adjustmentProducts.variant',
            'adjustmentProducts.branch:id,name,branch_code,area_name,parent_branch_id',
            'adjustmentProducts.branch.parentBranch:id,name,branch_code,area_name',
            'adjustmentProducts.warehouse:id,warehouse_name,warehouse_code',
            'adjustmentProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'adjustmentProducts.unit.baseUnit:id,base_unit_id,code_name',
            'createdBy:id,prefix,name,last_name',
        ]);

        $data['printPageSize'] = $request->print_page_size;

        return $data;
    }
}
