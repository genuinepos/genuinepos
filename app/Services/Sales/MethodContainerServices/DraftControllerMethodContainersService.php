<?php

namespace App\Services\Sales\MethodContainerServices;

use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\DayBookVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Enums\SaleStatus;
use App\Interfaces\Sales\DraftControllerMethodContainersInterface;

class DraftControllerMethodContainersService implements DraftControllerMethodContainersInterface
{
    public function showMethodContainer(
        int $id,
        object $quotationService,
        object $saleProductService,
    ): array {

        $data = [];
        $draft = $quotationService->singleDraft(id: $id, with: [
            'customer:id,name,phone,address',
            'createdBy:id,prefix,name,last_name',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.branch:id,name,branch_code,area_name,parent_branch_id',
            'saleProducts.branch.parentBranch:id,name,branch_code,area_name',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,base_unit_id,code_name',
        ]);

        $data['customerCopySaleProducts'] = $saleProductService->customerCopySaleProducts(saleId: $draft->id);
        $data['draft'] = $draft;

        return $data;
    }

    public function editMethodContainer(
        int $id,
        object $draftService,
        object $branchService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
        object $priceGroupService,
        object $warehouseService,
        object $managePriceGroupService,
    ): array {

        $draft = $draftService->singleDraft(id: $id, with: [
            'customer',
            'customer.group',
            'branch:id,parent_branch_id,name,branch_code,area_name',
            'branch.parentBranch:id,name',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,name,code_name,base_unit_id',
        ]);

        $generalSettings = config('generalSettings');
        $ownBranchIdOrParentBranchId = $draft?->branch?->parent_branch_id ? $draft?->branch?->parent_branch_id : $draft->branch_id;

        $data['branchName'] = $branchService->branchName($draft);

        $accounts = $accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $draft->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

        $data['accounts'] = $accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['warehouses'] = $warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['saleAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', $draft->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['taxAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $draft->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['customerAccounts'] = $accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $data['priceGroups'] = $priceGroupService->priceGroups()->get(['id', 'name']);
        $data['priceGroupProducts'] = $managePriceGroupService->priceGroupProducts();
        $data['draft'] = $draft;

        return $data;
    }

    public function updateMethodContainer(
        int $id,
        object $request,
        object $draftService,
        object $saleService,
        object $draftProductService,
        object $branchSettingService,
        object $dayBookService,
        object $accountLedgerService,
        object $productStockService,
        object $productLedgerService,
        object $purchaseProductService,
        object $accountService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $userActivityLogUtil,
        object $codeGenerator,
    ): ?array {

        $restrictions = $saleService->restrictions(request: $request, accountService: $accountService);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $branchSetting = $branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
        $invoicePrefix = isset($branchSetting) && $branchSetting?->sale_invoice_prefix ? $branchSetting?->sale_invoice_prefix : $generalSettings['prefix__sales_invoice_prefix'];
        $quotationPrefix = isset($branchSetting) && $branchSetting?->quotation_prefix ? $branchSetting?->quotation_prefix : 'Q';
        $salesOrderPrefix = isset($branchSetting) && $branchSetting?->sales_order_prefix ? $branchSetting?->sales_order_prefix : 'OR';
        $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt_voucher_prefix'];
        $stockAccountingMethod = $generalSettings['business__stock_accounting_method'];

        $draft = $draftService->singleDraft(id: $id, with: ['saleProducts']);

        $updateDraft = $draftService->updateDraft(request: $request, updateDraft: $draft, codeGenerator: $codeGenerator, salesOrderPrefix: $salesOrderPrefix, invoicePrefix: $invoicePrefix, quotationPrefix: $quotationPrefix);

        if ($request->status == SaleStatus::Final->value || $request->status == SaleStatus::Order->value) {

            $dayBookVoucherType = $request->status == SaleStatus::Final->value ? DayBookVoucherType::Sales->value : DayBookVoucherType::SalesOrder->value;

            // Add Day Book entry for Final Sale or Sales Order
            $dayBookService->addDayBook(voucherTypeId: $dayBookVoucherType, date: $request->date, accountId: $request->customer_account_id, transId: $updateDraft->id, amount: $request->total_invoice_amount, amountType: 'debit');
        }

        if ($request->status == SaleStatus::Final->value) {

            // Add Sale A/c Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, date: $request->date, account_id: $request->sale_account_id, trans_id: $updateDraft->id, amount: $request->sales_ledger_amount, amount_type: 'credit');

            // Add supplier A/c ledger Entry For Sales
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->customer_account_id, date: $request->date, trans_id: $updateDraft->id, amount: $request->total_invoice_amount, amount_type: 'debit');

            if ($request->sale_tax_ac_id) {

                // Add Tax A/c ledger Entry For Sales
                $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->sale_tax_ac_id, date: $request->date, trans_id: $updateDraft->id, amount: $request->order_tax_amount, amount_type: 'credit');
            }
        }

        foreach ($request->product_ids as $index => $productId) {

            $updateDraftProduct = $draftProductService->updateDraftProduct(request: $request, draft: $updateDraft, index: $index);

            if ($request->status == SaleStatus::Final->value) {

                // Add Product Ledger Entry
                $productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Sales->value, date: $request->date, productId: $productId, transId: $updateDraftProduct->id, rate: $updateDraftProduct->unit_price_inc_tax, quantityType: 'out', quantity: $updateDraftProduct->quantity, subtotal: $updateDraftProduct->subtotal, variantId: $updateDraftProduct->variant_id, warehouseId: (isset($request->warehouse_ids[$index]) ? $request->warehouse_ids[$index] : null));

                if ($updateDraftProduct->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SaleProductTax->value, date: $request->date, account_id: $updateDraftProduct->tax_ac_id, trans_id: $updateDraftProduct->id, amount: ($updateDraftProduct->unit_tax_amount * $updateDraftProduct->quantity), amount_type: 'credit');
                }
            }
        }

        if (($request->status == SaleStatus::Final->value || $request->status == SaleStatus::Order->value) && $request->received_amount > 0) {

            $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, saleRefId: $updateDraft->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount);

            //Add Debit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount, note: $request->payment_note);

            // Add Accounting VoucherDescription References
            $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: [$updateDraft->id]);

            //Add Credit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        $draft = $draftService->singleDraft(id: $id, with: ['saleProducts']);

        if ($draft->due > 0 && $draft->status == SaleStatus::Final->value) {

            $accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->customer_account_id, accountingVoucherType: AccountingVoucherType::Receipt->value, refIdColName: 'sale_id', sale: $draft);
        }

        if ($request->status == SaleStatus::Final->value) {

            foreach ($request->product_ids as $__index => $productId) {

                $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                $productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

                $productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);

                if (isset($request->warehouse_ids[$__index])) {

                    $productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_ids[$__index]);
                } else {

                    $productStockService->adjustBranchStock($productId, $variantId, branchId: auth()->user()->branch_id);
                }

                $purchaseProductService->addPurchaseSaleProductChain($draft, $stockAccountingMethod);
            }
        }

        $deletedUnusedDraftProducts = $draft->saleProducts()->where('is_delete_in_update', 1)->get();

        if (count($deletedUnusedDraftProducts) > 0) {

            foreach ($deletedUnusedDraftProducts as $deletedUnusedDraftProduct) {

                $deletedUnusedDraftProduct->delete();
            }
        }

        $subjectType = '';
        if ($request->status == SaleStatus::Final->value) {

            $subjectType = 7;
        } elseif ($request->status == SaleStatus::Order->value) {

            $subjectType = 8;
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $subjectType = 30;
        }

        if ($subjectType) {

            $userActivityLogUtil->addLog(action: 1, subject_type: $subjectType, data_obj: $draft);
        }

        $userActivityLogUtil->addLog(action: 2, subject_type: 29, data_obj: $draft);

        return null;
    }
}
