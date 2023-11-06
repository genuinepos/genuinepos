<?php

namespace App\Services\Sales\MethodContainerServices;

use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\DayBookVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Enums\SaleScreenType;
use App\Enums\SaleStatus;
use App\Interfaces\Sales\AddSaleControllerMethodContainersInterface;

class AddSaleControllerMethodContainersService implements AddSaleControllerMethodContainersInterface
{
    public function showMethodContainer(
        int $id,
        object $saleService,
        object $saleProductService,
    ): ?array {

        $data = [];
        $sale = $saleService->singleSale(id: $id, with: [
            'branch',
            'branch.parentBranch',
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

            'references:id,voucher_description_id,sale_id,amount',
            'references.voucherDescription:id,accounting_voucher_id',
            'references.voucherDescription.accountingVoucher:id,voucher_no,date,voucher_type',
            'references.voucherDescription.accountingVoucher.voucherDescriptions:id,accounting_voucher_id,account_id,payment_method_id',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.paymentMethod:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.bank:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.group:id,sub_sub_group_number',
        ]);

        $data['customerCopySaleProducts'] = $saleProductService->customerCopySaleProducts(saleId: $sale->id);
        $data['sale'] = $sale;

        return $data;
    }

    public function createMethodContainer(
        object $branchService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
        object $warehouseService,
        object $priceGroupService,
        object $managePriceGroupService,
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

        $data['saleAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['warehouses'] = $warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['taxAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['customerAccounts'] = $accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $data['priceGroupProducts'] = $managePriceGroupService->priceGroupProducts();

        $data['priceGroups'] = $priceGroupService->priceGroups()->get(['id', 'name']);

        return $data;
    }

    public function storeMethodContainer(
        object $request,
        object $branchSettingService,
        object $saleService,
        object $saleProductService,
        object $dayBookService,
        object $accountService,
        object $accountLedgerService,
        object $productStockService,
        object $productLedgerService,
        object $purchaseProductService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $userActivityLogUtil,
        object $codeGenerator,
    ): ?array {

        $generalSettings = config('generalSettings');
        $branchSetting = $branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
        $invoicePrefix = isset($branchSetting) && $branchSetting?->sale_invoice_prefix ? $branchSetting?->sale_invoice_prefix : $generalSettings['prefix__sale_invoice'];
        $quotationPrefix = isset($branchSetting) && $branchSetting?->quotation_prefix ? $branchSetting?->quotation_prefix : 'Q';
        $salesOrderPrefix = isset($branchSetting) && $branchSetting?->sales_order_prefix ? $branchSetting?->sales_order_prefix : 'OR';
        $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt'];
        $stockAccountingMethod = $generalSettings['business__stock_accounting_method'];

        $restrictions = $saleService->restrictions(request: $request, accountService: $accountService);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $addSale = $saleService->addSale(request: $request, saleScreenType: SaleScreenType::AddSale->value, codeGenerator: $codeGenerator, invoicePrefix: $invoicePrefix, quotationPrefix: $quotationPrefix, salesOrderPrefix: $salesOrderPrefix);

        if ($request->status == SaleStatus::Final->value || $request->status == SaleStatus::Order->value) {

            $dayBookVoucherType = $request->status == SaleStatus::Final->value ? DayBookVoucherType::Sales->value : DayBookVoucherType::SalesOrder->value;

            // Add Day Book entry for Final Sale or Sales Order
            $dayBookService->addDayBook(voucherTypeId: $dayBookVoucherType, date: $request->date, accountId: $request->customer_account_id, transId: $addSale->id, amount: $request->total_invoice_amount, amountType: 'debit');
        }

        if ($request->status == SaleStatus::Final->value) {

            // Add Sale A/c Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, date: $request->date, account_id: $request->sale_account_id, trans_id: $addSale->id, amount: $request->sales_ledger_amount, amount_type: 'credit');

            // Add supplier A/c ledger Entry For Purchase
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->customer_account_id, date: $request->date, trans_id: $addSale->id, amount: $request->total_invoice_amount, amount_type: 'debit');

            if ($request->sale_tax_ac_id) {

                // Add Tax A/c ledger Entry For Purchase
                $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->sale_tax_ac_id, date: $request->date, trans_id: $addSale->id, amount: $request->order_tax_amount, amount_type: 'credit');
            }
        }

        $index = 0;
        foreach ($request->product_ids as $productId) {

            $addSaleProduct = $saleProductService->addSaleProduct(request: $request, sale: $addSale, index: $index);

            if ($request->status == SaleStatus::Final->value) {

                // Add Product Ledger Entry
                $productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Sales->value, date: $request->date, productId: $productId, transId: $addSaleProduct->id, rate: $addSaleProduct->unit_price_inc_tax, quantityType: 'out', quantity: $addSaleProduct->quantity, subtotal: $addSaleProduct->subtotal, variantId: $addSaleProduct->variant_id, warehouseId: (isset($request->warehouse_ids[$index]) ? $request->warehouse_ids[$index] : null));

                if ($addSaleProduct->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SaleProductTax->value, date: $request->date, account_id: $addSaleProduct->tax_ac_id, trans_id: $addSaleProduct->id, amount: ($addSaleProduct->unit_tax_amount * $addSaleProduct->quantity), amount_type: 'credit');
                }
            }

            $index++;
        }

        if (($request->status == SaleStatus::Final->value || $request->status == SaleStatus::Order->value) && $request->received_amount > 0) {

            $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, saleRefId: $addSale->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount);

            //Add Debit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount, note: $request->payment_note);

            // Add Accounting VoucherDescription References
            $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: [$addSale->id]);

            //Add Credit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        $sale = $saleService->singleSale(
            id: $addSale->id,
            with: [
                'branch',
                'branch.parentBranch',
                'branch.branchSetting:id,add_sale_invoice_layout_id',
                'branch.branchSetting.addSaleInvoiceLayout',
                'customer',
                'saleProducts',
                'saleProducts.product',
            ]
        );

        if ($sale->due > 0 && $sale->status == SaleStatus::Final->value) {

            $accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->customer_account_id, accountingVoucherType: AccountingVoucherType::Receipt->value, refIdColName: 'sale_id', sale: $sale);
        }

        if ($request->status == SaleStatus::Final->value) {

            $__index = 0;
            foreach ($request->product_ids as $productId) {

                $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                $productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

                $productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);

                if (isset($request->warehouse_ids[$__index])) {

                    $productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_ids[$__index]);
                } else {

                    $productStockService->adjustBranchStock($productId, $variantId, branchId: auth()->user()->branch_id);
                }

                $purchaseProductService->addPurchaseSaleProductChain($sale, $stockAccountingMethod);

                $__index++;
            }
        }

        $customerCopySaleProducts = $saleProductService->customerCopySaleProducts(saleId: $sale->id);

        $subjectType = '';
        if ($request->status == SaleStatus::Final->value) {
            $subjectType = 7;
        } elseif ($request->status == SaleStatus::Order->value) {
            $subjectType = 8;
        } elseif ($request->status == SaleStatus::Quotation->value) {
            $subjectType = 30;
        } elseif ($request->status == SaleStatus::Draft->value) {
            $subjectType = 29;
        }

        $userActivityLogUtil->addLog(action: 1, subject_type: $subjectType, data_obj: $sale);

        return ['sale' => $sale, 'customerCopySaleProducts' => $customerCopySaleProducts];
    }

    public function editMethodContainer(
        int $id,
        object $branchService,
        object $saleService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
        object $warehouseService,
        object $priceGroupService,
        object $managePriceGroupService,
    ): array {

        $sale = $saleService->singleSale(id: $id, with: [
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,name,code_name,base_unit_id',
        ]);

        $ownBranchIdOrParentBranchId = $sale?->branch?->parent_branch_id ? $sale?->branch?->parent_branch_id : $sale->branch_id;

        $data['branchName'] = $branchService->branchName(transObject: $sale);

        $accounts = $accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $sale->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

        $data['accounts'] = $accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['saleAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', $sale->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['warehouses'] = $warehouseService->warehouses()->where('branch_id', $sale->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['taxAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $sale->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['customerAccounts'] = $accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $data['priceGroupProducts'] = $managePriceGroupService->priceGroupProducts();

        $data['priceGroups'] = $priceGroupService->priceGroups()->get(['id', 'name']);
        $data['sale'] = $sale;

        return $data;
    }

    public function updateMethodContainer(
        int $id,
        object $request,
        object $branchSettingService,
        object $saleService,
        object $saleProductService,
        object $dayBookService,
        object $accountService,
        object $accountLedgerService,
        object $productStockService,
        object $productLedgerService,
        object $purchaseProductService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $userActivityLogUtil,
        object $codeGenerator,
    ): ?array {

        $restrictions = $saleService->restrictions(request: $request, accountService: $accountService, checkCustomerChangeRestriction: true, saleId: $id);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $branchSetting = $branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
        $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt'];
        $stockAccountingMethod = $generalSettings['business__stock_accounting_method'];

        $sale = $saleService->singleSale(id: $id, with: ['saleProducts']);

        $storedCurrSaleAccountId = $sale->sale_account_id;
        $storedCurrCustomerAccountId = $sale->customer_account_id;
        $storedCurrSaleTaxAccountId = $sale->sale_tax_ac_id;

        $updateSale = $saleService->updateSale(request: $request, updateSale: $sale);

        // Update Day Book entry for Sale
        $dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Sales->value, date: $request->date, accountId: $request->customer_account_id, transId: $updateSale->id, amount: $request->total_invoice_amount, amountType: 'debit');

        // Update Sale A/c Ledger Entry
        $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, date: $request->date, account_id: $request->sale_account_id, trans_id: $updateSale->id, amount: $request->sales_ledger_amount, amount_type: 'credit', current_account_id: $storedCurrSaleAccountId, branch_id: $updateSale->branch_id);

        // Update customer A/c ledger Entry For sale
        $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->customer_account_id, date: $request->date, trans_id: $updateSale->id, amount: $request->total_invoice_amount, amount_type: 'debit', current_account_id: $storedCurrSaleTaxAccountId, branch_id: $updateSale->branch_id);

        if ($request->sale_tax_ac_id) {

            // Add Tax A/c ledger Entry For Sale
            $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->sale_tax_ac_id, date: $request->date, trans_id: $updateSale->id, amount: $request->order_tax_amount, amount_type: 'debit', current_account_id: $storedCurrSaleTaxAccountId, branch_id: $updateSale->branch_id);
        } else {

            $accountLedgerService->deleteUnusedLedgerEntry(voucherType: AccountLedgerVoucherType::Sales->value, transId: $updateSale->id, accountId: $storedCurrSaleTaxAccountId);
        }

        $index = 0;
        foreach ($request->product_ids as $productId) {

            $updateSaleProduct = $saleProductService->updateSaleProduct(request: $request, sale: $updateSale, index: $index);

            // Add Product Ledger Entry
            $productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Sales->value, date: $request->date, productId: $productId, transId: $updateSaleProduct->id, rate: $updateSaleProduct->unit_price_inc_tax, quantityType: 'out', quantity: $updateSaleProduct->quantity, subtotal: $updateSaleProduct->subtotal, variantId: $updateSaleProduct->variant_id, warehouseId: (isset($request->warehouse_ids[$index]) ? $request->warehouse_ids[$index] : null), currentWarehouseId: $updateSaleProduct->current_warehouse_id, branchId: $updateSale->branch_id);

            if ($updateSaleProduct->tax_ac_id) {

                // Add Tax A/c ledger Entry
                $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SaleProductTax->value, date: $request->date, account_id: $updateSaleProduct->tax_ac_id, trans_id: $updateSaleProduct->id, amount: ($updateSaleProduct->unit_tax_amount * $updateSaleProduct->quantity), amount_type: 'credit', current_account_id: $updateSaleProduct->current_tax_ac_id, branch_id: $updateSale->branch_id);
            } else {

                $accountLedgerService->deleteUnusedLedgerEntry(voucherType: AccountLedgerVoucherType::SaleProductTax->value, transId: $updateSaleProduct->id, accountId: $updateSaleProduct->current_tax_ac_id);
            }

            $index++;
        }

        if ($request->received_amount > 0) {

            $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, saleRefId: $updateSale->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount);

            //Add Debit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount, note: $request->payment_note);

            // Add Accounting VoucherDescription References
            $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: [$updateSale->id]);

            //Add Credit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        $deletedUnusedSaleProducts = $saleProductService->saleProducts(with: ['purchaseSaleProductChains', 'purchaseSaleProductChains.purchaseProduct'])->where('sale_id', $updateSale->id)->where('is_delete_in_update', 1)->get();

        if (count($deletedUnusedSaleProducts) > 0) {

            foreach ($deletedUnusedSaleProducts as $deletedUnusedSaleProduct) {

                $deletedUnusedSaleProduct->delete();

                // Adjust deleted product stock
                $productStockService->adjustMainProductAndVariantStock($deletedUnusedSaleProduct->product_id, $deletedUnusedSaleProduct->variant_id);

                $productStockService->adjustBranchAllStock(productId: $deletedUnusedSaleProduct->product_id, variantId: $deletedUnusedSaleProduct->variant_id, branchId: $updateSale->branch_id);

                if (isset($deletedUnusedSaleProduct->warehouse_id)) {

                    $productStockService->adjustWarehouseStock(productId: $deletedUnusedSaleProduct->product_id, variantId: $deletedUnusedSaleProduct->variant_id, warehouseId: $deletedUnusedSaleProduct->warehouse_id);
                } else {

                    $productStockService->adjustBranchStock(productId: $deletedUnusedSaleProduct->product_id, ariantId: $deletedUnusedSaleProduct->variant_id, branchId: $updateSale->branch_id);
                }

                foreach ($deletedUnusedSaleProduct->purchaseSaleProductChains as $purchaseSaleProductChain) {

                    $purchaseProductService->adjustPurchaseProductSaleLeftQty($purchaseSaleProductChain->purchaseProduct);
                }
            }
        }

        $sale = $saleService->singleSale(id: $updateSale->id, with: [
            'saleProducts',
            'saleProducts.product',
            'saleProducts.purchaseSaleProductChains',
            'saleProducts.purchaseSaleProductChains.purchaseProduct',
        ]);

        if ($sale->due > 0 && $sale->status == SaleStatus::Final->value) {

            $accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->customer_account_id, accountingVoucherType: AccountingVoucherType::Receipt->value, refIdColName: 'sale_id', sale: $sale);
        }

        $saleProducts = $sale->saleProducts;
        foreach ($saleProducts as $saleProduct) {

            $productStockService->adjustMainProductAndVariantStock($saleProduct->product_id, $saleProduct->variant_id);

            $productStockService->adjustBranchAllStock(productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, branchId: $updateSale->branch_id);

            if ($saleProduct->warehouse_id) {

                $productStockService->adjustWarehouseStock(productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, warehouseId: $saleProduct->warehouse_id);
            } else {

                $productStockService->adjustBranchStock(productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, branchId: $saleProduct->branch_id);
            }
        }

        $purchaseProductService->updatePurchaseSaleProductChain($sale, $stockAccountingMethod);

        $saleService->adjustSaleInvoiceAmounts(sale: $sale);

        // Add user Log
        $userActivityLogUtil->addLog(action: 2, subject_type: 7, data_obj: $sale);

        return null;
    }

    public function deleteMethodContainer(
        int $id,
        object $saleService,
        object $productStockService,
        object $purchaseProductService,
        object $userActivityLogUtil,
    ): array|object {

        $deleteSale = $saleService->deleteSale($id);

        if (isset($deleteSale['pass']) && $deleteSale['pass'] == false) {

            return ['pass' => false, 'msg' => $deleteSale['msg']];
        }

        if ($deleteSale->status == SaleStatus::Final->value) {

            foreach ($deleteSale->saleProducts as $saleProduct) {

                $productStockService->adjustMainProductAndVariantStock($saleProduct->product_id, $saleProduct->variant_id);

                $productStockService->adjustBranchAllStock(productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, branchId: $saleProduct->branch_id);

                if ($saleProduct->warehouse_id) {

                    $productStockService->adjustWarehouseStock($saleProduct->product_id, $saleProduct->variant_id, $saleProduct->warehouse_id);
                } else {

                    $productStockService->adjustBranchStock($saleProduct->product_id, $saleProduct->variant_id, $saleProduct->branch_id);
                }

                foreach ($saleProduct->purchaseSaleProductChains as $purchaseSaleProductChain) {

                    if ($purchaseSaleProductChain->purchaseProduct) {

                        $purchaseProductService->adjustPurchaseProductSaleLeftQty($purchaseSaleProductChain->purchaseProduct);
                    }
                }
            }
        }

        $subjectType = '';
        if ($deleteSale->status == SaleStatus::Final->value) {
            $subjectType = 7;
        } elseif ($deleteSale->status == SaleStatus::Order->value) {
            $subjectType = 8;
        } elseif ($deleteSale->status == SaleStatus::Quotation->value) {
            $subjectType = 30;
        } elseif ($deleteSale->status == SaleStatus::Draft->value) {
            $subjectType = 29;
        }

        $userActivityLogUtil->addLog(action: 3, subject_type: $subjectType, data_obj: $deleteSale);

        return $deleteSale;
    }
}
