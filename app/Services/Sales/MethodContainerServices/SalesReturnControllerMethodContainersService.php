<?php

namespace App\Services\Sales\MethodContainerServices;

use App\Enums\BooleanType;
use App\Enums\DayBookVoucherType;
use App\Services\Sales\SaleService;
use App\Enums\AccountingVoucherType;
use App\Services\Setups\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\WarehouseService;
use App\Services\Sales\SalesReturnService;
use App\Services\Products\PriceGroupService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Sales\SalesReturnProductService;
use App\Services\Products\ManagePriceGroupService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Interfaces\Sales\SalesReturnControllerMethodContainersInterface;

class SalesReturnControllerMethodContainersService implements SalesReturnControllerMethodContainersInterface
{
    public function __construct(
        private SaleService $saleService,
        private SalesReturnService $salesReturnService,
        private SalesReturnProductService $salesReturnProductService,
        private PurchaseProductService $purchaseProductService,
        private PaymentMethodService $paymentMethodService,
        private PriceGroupService $priceGroupService,
        private ManagePriceGroupService $managePriceGroupService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private AccountLedgerService $accountLedgerService,
        private ProductLedgerService $productLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private UserActivityLogService $userActivityLogService,
    ) {
    }

    public function indexMethodContainer(object $request): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->salesReturnService->salesReturnListTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $data['customerAccounts'] = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return $data;
    }

    public function showMethodContainer(int $id): ?array
    {
        $data = [];
        $data['return'] = $this->salesReturnService->singleSalesReturn(id: $id, with: [
            'sale',
            'customer:id,name,phone,address,account_group_id',
            'customer.group',
            'createdBy:id,prefix,name,last_name',
            'saleReturnProducts',
            'saleReturnProducts.product',
            'saleReturnProducts.variant',
            'saleReturnProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'saleReturnProducts.unit.baseUnit:id,base_unit_id,code_name',

            'references:id,voucher_description_id,sale_return_id,amount',
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

    public function printMethodContainer(int $id, object $request): mixed
    {
        $data = [];
        $data['return'] = $this->salesReturnService->singleSalesReturn(id: $id, with: [
            'sale',
            'customer:id,name,phone,address,account_group_id',
            'customer.group',
            'createdBy:id,prefix,name,last_name',
            'saleReturnProducts',
            'saleReturnProducts.product',
            'saleReturnProducts.variant',
            'saleReturnProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'saleReturnProducts.unit.baseUnit:id,base_unit_id,code_name',
        ]);

        $data['printPageSize'] = $request->print_page_size;
        $data['paidAmount'] = $return->paid;

        return $data;
    }

    public function createMethodContainer(): ?array
    {
        $data = [];
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $data['branchName'] = $this->branchService->branchName();

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['saleAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['warehouses'] = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['priceGroups'] = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        $data['priceGroupProducts'] = $this->managePriceGroupService->priceGroupProducts();

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['customerAccounts'] = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): ?array
    {
        $restrictions = $this->salesReturnService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $salesReturnVoucherPrefix = $generalSettings['prefix__sales_return_prefix'] ? $generalSettings['prefix__sales_return_prefix'] : 'SR';
        $paymentVoucherPrefix = $generalSettings['prefix__payment_voucher_prefix'] ? $generalSettings['prefix__payment_voucher_prefix'] : 'PV';

        $addReturn = $this->salesReturnService->addSalesReturn(request: $request, voucherPrefix: $salesReturnVoucherPrefix, codeGenerator: $codeGenerator);

        $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::SalesReturn->value, date: $request->date, accountId: $request->customer_account_id, transId: $addReturn->id, amount: $request->total_return_amount, amountType: 'credit');

        // Add sales A/c Ledger Entry
        $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SalesReturn->value, date: $request->date, account_id: $request->sale_account_id, trans_id: $addReturn->id, amount: $request->sale_ledger_amount, amount_type: 'debit');

        // Add Customer A/c ledger Entry For Sales Return
        $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SalesReturn->value, account_id: $request->customer_account_id, date: $request->date, trans_id: $addReturn->id, amount: $request->total_return_amount, amount_type: 'credit');

        if ($request->return_tax_ac_id) {

            // Add Tax A/c ledger Entry For Sales Return
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SalesReturn->value, account_id: $request->return_tax_ac_id, date: $request->date, trans_id: $addReturn->id, amount: $request->return_tax_amount, amount_type: 'debit');
        }

        foreach ($request->product_ids as $index => $productId) {

            $addSaleReturnProduct = $this->salesReturnProductService->addSalesReturnProduct(request: $request, saleReturnId: $addReturn->id, index: $index);

            // Add Product Ledger Entry
            $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::SalesReturn->value, date: $request->date, productId: $productId, transId: $addSaleReturnProduct->id, rate: $addSaleReturnProduct->unit_price_inc_tax, quantityType: 'in', quantity: $addSaleReturnProduct->return_qty, subtotal: $addSaleReturnProduct->return_subtotal, variantId: $addSaleReturnProduct->variant_id, warehouseId: (isset($addReturn->warehouse_id) ? $addReturn->warehouse_id : null));

            // Sales Return product tax will be go here
            if ($addSaleReturnProduct->tax_ac_id) {

                // Add Tax A/c ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SalesReturnProductTax->value, date: $request->date, account_id: $addSaleReturnProduct->tax_ac_id, trans_id: $addSaleReturnProduct->id, amount: ($addSaleReturnProduct->unit_tax_amount * $addSaleReturnProduct->return_qty), amount_type: 'debit');
            }

            if ($addSaleReturnProduct->return_qty > 0) {

                $this->purchaseProductService->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(transColName: 'sale_return_product_id', transId: $addSaleReturnProduct->id, branchId: auth()->user()->branch_id, productId: $addSaleReturnProduct->product_id, variantId: $addSaleReturnProduct->variant_id, quantity: $addSaleReturnProduct->return_qty, unitCostIncTax: $addSaleReturnProduct->unit_cost_inc_tax, sellingPrice: $addSaleReturnProduct->unit_price_inc_tax, subTotal: $addSaleReturnProduct->return_subtotal, createdAt: $addReturn->date_ts);
            }
        }

        if ($request->paying_amount > 0) {

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Payment->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount, saleReturnRefId: $addReturn->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherDebitDescription->id, accountId: $request->customer_account_id, amount: $request->paying_amount, refIdColName: 'sale_return_id', refIds: [$addReturn->id]);

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->account_id);

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, note: $request->payment_note);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');
        }

        foreach ($request->product_ids as $__index => $productId) {

            $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
            $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

            $this->productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);

            if (isset($request->warehouse_count) && $request->warehouse_id) {

                $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);
            }
        }

        $return = $this->salesReturnService->singleSalesReturn(id: $addReturn->id, with: [
            'sale',
            'branch',
            'branch.parentBranch',
            'customer',
            'saleReturnProducts',
            'saleReturnProducts.product',
            'saleReturnProducts.variant',
            'saleReturnProducts.unit',
        ]);

        if ($return?->sale) {

            $this->saleService->adjustSaleInvoiceAmounts($return->sale);
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::SaleReturn->value, dataObj: $return);

        $paidAmount = $request->paying_amount;
        $printPageSize = $request->print_page_size;

        return ['return' => $return, 'paidAmount' => $paidAmount, 'printPageSize' => $printPageSize];
    }

    public function editMethodContainer(int $id): array
    {
        $data = [];
        $return = $this->salesReturnService->singleSalesReturn(id: $id, with: [
            'branch',
            'branch.parentBranch',
            'sale',
            'saleReturnProducts',
            'saleReturnProducts.product',
            'saleReturnProducts.variant',
            'saleReturnProducts.unit:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleReturnProducts.unit.baseUnit:id,base_unit_id,code_name',
        ]);

        $ownBranchIdOrParentBranchId = $return?->branch?->parent_branch_id ? $return?->branch?->parent_branch_id : $return?->branch_id;

        $data['branchName'] = $this->branchService->branchName(transObject: $return);

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['saleAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['warehouses'] = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['priceGroups'] = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        $data['priceGroupProducts'] = $this->managePriceGroupService->priceGroupProducts();

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['customerAccounts'] = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $data['return'] = $return;

        return $data;
    }

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array
    {
        $restrictions = $this->salesReturnService->restrictions(request: $request, checkCustomerChangeRestriction: true, saleReturnId: $id);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $paymentVoucherPrefix = $generalSettings['prefix__payment_voucher_prefix'] ? $generalSettings['prefix__payment_voucher_prefix'] : 'PV';

        $return = $this->salesReturnService->singleSalesReturn(id: $id, with: ['saleReturnProducts']);

        $storedCurrWarehouseId = $return->warehouse_id;
        $storedCurrParentSaleId = $return->sale_id;
        $storedCurrSaleAccountId = $return->sale_account_id;
        $storedCurrCustomerAccountId = $return->customer_account_id;
        $storedCurrReturnTaxAccountId = $return->return_tax_ac_id;
        $storedSalesReturnProducts = $return->saleReturnProducts;

        $updateReturn = $this->salesReturnService->updateSalesReturn(request: $request, return: $return);

        $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::SalesReturn->value, date: $request->date, accountId: $request->customer_account_id, transId: $updateReturn->id, amount: $request->total_return_amount, amountType: 'credit');

        // Add sales A/c Ledger Entry
        $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SalesReturn->value, date: $request->date, account_id: $request->sale_account_id, trans_id: $updateReturn->id, amount: $request->sale_ledger_amount, amount_type: 'debit', branch_id: $updateReturn->branch_id, current_account_id: $storedCurrSaleAccountId);

        // Add Customer A/c ledger Entry For Sales Return
        $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SalesReturn->value, account_id: $request->customer_account_id, date: $request->date, trans_id: $updateReturn->id, amount: $request->total_return_amount, amount_type: 'credit', branch_id: $updateReturn->branch_id, current_account_id: $storedCurrCustomerAccountId);

        if ($request->return_tax_ac_id) {

            // Add Tax A/c ledger Entry For Sales Return
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SalesReturn->value, account_id: $request->return_tax_ac_id, date: $request->date, trans_id: $updateReturn->id, amount: $request->return_tax_amount, amount_type: 'debit', branch_id: $updateReturn->branch_id, current_account_id: $storedCurrReturnTaxAccountId);
        } else {

            $this->accountLedgerService->deleteUnusedLedgerEntry(voucherType: AccountLedgerVoucherType::SalesReturn->value, transId: $updateReturn->id, accountId: $storedCurrReturnTaxAccountId);
        }

        foreach ($request->product_ids as $index => $productId) {

            $updateSaleReturnProduct = $this->salesReturnProductService->updateSalesReturnProduct(request: $request, saleReturnId: $updateReturn->id, index: $index);

            // Update Product Ledger Entry
            $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::SalesReturn->value, date: $request->date, productId: $productId, transId: $updateSaleReturnProduct->id, rate: $updateSaleReturnProduct->unit_price_inc_tax, quantityType: 'in', quantity: $updateSaleReturnProduct->return_qty, subtotal: $updateSaleReturnProduct->return_subtotal, variantId: $updateSaleReturnProduct->variant_id, warehouseId: (isset($updateReturn->warehouse_id) ? $updateReturn->warehouse_id : null), currentWarehouseId: $storedCurrWarehouseId, branchId: $updateReturn->branch_id);

            // Sales Return product tax ledger
            if ($updateSaleReturnProduct->tax_ac_id) {

                // Update Tax A/c ledger Entry
                $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SalesReturnProductTax->value, date: $request->date, account_id: $updateSaleReturnProduct->tax_ac_id, trans_id: $updateSaleReturnProduct->id, amount: ($addSaleReturnProduct->unit_tax_amount * $updateSaleReturnProduct->return_qty), amount_type: 'debit', branch_id: $updateReturn->branch_id, current_account_id: $updateSaleReturnProduct->current_tax_ac_id);
            } else {

                $this->accountLedgerService->deleteUnusedLedgerEntry(voucherType: AccountLedgerVoucherType::PurchaseReturnProductTax->value, transId: $updateSaleReturnProduct->id, accountId: $updateSaleReturnProduct->current_tax_ac_id);
            }

            $this->purchaseProductService->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(transColName: 'sale_return_product_id', transId: $updateSaleReturnProduct->id, branchId: auth()->user()->branch_id, productId: $updateSaleReturnProduct->product_id, variantId: $updateSaleReturnProduct->variant_id, quantity: $updateSaleReturnProduct->return_qty, unitCostIncTax: $updateSaleReturnProduct->unit_cost_inc_tax, sellingPrice: $updateSaleReturnProduct->unit_price_inc_tax, subTotal: $updateSaleReturnProduct->return_subtotal, createdAt: $updateReturn->date_ts);
        }

        if ($request->paying_amount > 0) {

            $paymentDate = $request->payment_date ? $request->payment_date : $request->date;

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $paymentDate, voucherType: AccountingVoucherType::Payment->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount, saleReturnRefId: $updateReturn->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherDebitDescription->id, accountId: $request->customer_account_id, amount: $request->paying_amount, refIdColName: 'sale_return_id', refIds: [$updateReturn->id]);

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $paymentDate, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->account_id);

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, note: $request->payment_note);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $paymentDate, account_id: $request->account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');
        }

        foreach ($request->product_ids as $__index => $productId) {

            $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
            $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

            $this->productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);

            if (isset($request->warehouse_count) && $request->warehouse_id) {

                $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);
            }
        }

        $deletedUnusedSalesReturnProducts = $this->salesReturnProductService->salesReturnProducts()->where('sale_return_id', $updateReturn->id)->where('is_delete_in_update', BooleanType::True->value)->get();
        if (count($deletedUnusedSalesReturnProducts) > 0) {

            foreach ($deletedUnusedSalesReturnProducts as $deletedUnusedSalesReturnProduct) {

                $deletedUnusedSalesReturnProduct->delete();

                // Adjust deleted product stock
                $this->productStockService->adjustMainProductAndVariantStock($deletedUnusedSalesReturnProduct->product_id, $deletedUnusedSalesReturnProduct->variant_id);

                $this->productStockService->adjustBranchAllStock(productId: $deletedUnusedSalesReturnProduct->product_id, variantId: $deletedUnusedSalesReturnProduct->variant_id, branchId: $updateReturn->branch_id);

                if (isset($storedCurrWarehouseId)) {

                    $this->productStockService->adjustWarehouseStock($deletedUnusedSalesReturnProduct->product_id, $deletedUnusedSalesReturnProduct->variant_id, $storedCurrWarehouseId);
                } else {

                    $this->productStockService->adjustBranchStock($deletedUnusedSalesReturnProduct->product_id, $deletedUnusedSalesReturnProduct->variant_id, $updateReturn->branch_id);
                }
            }
        }

        if (isset($request->warehouse_count) && $storedCurrWarehouseId && $request->warehouse_id != $storedCurrWarehouseId) {

            foreach ($storedSalesReturnProducts as $storedSalesReturnProduct) {

                $this->productStockService->adjustWarehouseStock($storedSalesReturnProduct->product_id, $storedSalesReturnProduct->variant_id, $storedCurrWarehouseId);
            }
        }

        if ($return?->sale_id) {

            $sale = $this->saleService->singleSale(id: $return?->sale_id);
            $this->saleService->adjustSaleInvoiceAmounts(sale: $sale);
        }

        if (isset($storedCurrParentSaleId)) {

            $sale = $this->saleService->singleSale(id: $storedCurrParentSaleId);
            $this->saleService->adjustSaleInvoiceAmounts(sale: $sale);
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::SaleReturn->value, dataObj: $updateReturn);

        return null;
    }

    public function deleteMethodContainer(int $id): array|object
    {
        $deleteSalesReturn = $this->salesReturnService->deleteSalesReturn(id: $id);

        if (isset($deleteSalesReturn['pass']) && $deleteSalesReturn['pass'] == false) {

            return ['pass' => false, 'msg' => $deleteSalesReturn['msg']];
        }

        foreach ($deleteSalesReturn->saleReturnProducts as $returnProduct) {

            $this->productStockService->adjustMainProductAndVariantStock($returnProduct->product_id, $returnProduct->variant_id);

            $this->productStockService->adjustBranchAllStock($returnProduct->product_id, $returnProduct->variant_id, $deleteSalesReturn->branch_id);

            if (isset($deleteSalesReturn->warehouse_id)) {

                $this->productStockService->adjustWarehouseStock($returnProduct->product_id, $returnProduct->variant_id, $deleteSalesReturn->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock($returnProduct->product_id, $returnProduct->variant_id, $deleteSalesReturn->branch_id);
            }
        }

        if ($deleteSalesReturn?->sale) {

            $this->saleService->adjustSaleInvoiceAmounts(sale: $deleteSalesReturn?->sale);
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::SaleReturn->value, dataObj: $deleteSalesReturn);
    }
}
