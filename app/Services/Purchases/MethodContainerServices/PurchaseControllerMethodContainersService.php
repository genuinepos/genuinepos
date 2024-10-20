<?php

namespace App\Services\Purchases\MethodContainerServices;

use App\Enums\BooleanType;
use App\Enums\PurchaseStatus;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use App\Services\Branches\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Products\ProductService;
use App\Services\Setups\WarehouseService;
use App\Services\Purchases\PurchaseService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Interfaces\Purchases\PurchaseControllerMethodContainersInterface;

class PurchaseControllerMethodContainersService implements PurchaseControllerMethodContainersInterface
{
    public function __construct(
        private PurchaseService $purchaseService,
        private PurchaseProductService $purchaseProductService,
        private UserActivityLogService $userActivityLogService,
        private PaymentMethodService $paymentMethodService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
        private ProductService $productService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private AccountLedgerService $accountLedgerService,
        private ProductLedgerService $productLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
    ) {}

    public function indexMethodContainer(object $request, ?int $supplierAccountId = null): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->purchaseService->purchaseListTable(request: $request, supplierAccountId: $supplierAccountId);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $data['purchaseAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['supplierAccounts'] = $this->accountService->customerAndSupplierAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId, sortingByGroupNumber: 'desc');

        return $data;
    }

    public function showMethodContainer($id): ?array
    {
        $purchase = $this->purchaseService->singlePurchase(id: $id, with: [
            'purchaseOrder:id,invoice_id',

            'warehouse:id,warehouse_name,warehouse_code',

            'supplier:id,name,phone,address,account_group_id',
            'supplier.group:id,sub_sub_group_number',

            'admin:id,prefix,name,last_name',
            'purchaseAccount:id,name',

            'purchaseProducts',
            'purchaseProducts.product',
            'purchaseProducts.product.warranty',
            'purchaseProducts.variant',
            'purchaseProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'purchaseProducts.unit.baseUnit:id,base_unit_id,code_name',

            'references:id,voucher_description_id,purchase_id,amount',
            'references.voucherDescription:id,accounting_voucher_id',
            'references.voucherDescription.accountingVoucher:id,voucher_no,date,voucher_type',
            'references.voucherDescription.accountingVoucher.voucherDescriptions:id,accounting_voucher_id,account_id,payment_method_id',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.paymentMethod:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.bank:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.group:id,sub_sub_group_number',
        ]);

        return ['purchase' => $purchase];
    }

    public function printMethodContainer(object $request, int $id): ?array
    {
        $purchase = $this->purchaseService->singlePurchase(id: $id, with: [
            'warehouse:id,warehouse_name,warehouse_code',
            'supplier:id,name,phone,address,account_group_id',
            'supplier.group:id,sub_sub_group_number',
            'admin:id,prefix,name,last_name',
            'purchaseAccount:id,name',
            'purchaseProducts',
            'purchaseProducts.product',
            'purchaseProducts.product.warranty',
            'purchaseProducts.variant',
            'purchaseProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'purchaseProducts.unit.baseUnit:id,base_unit_id,code_name',
        ]);

        $printPageSize = $request->print_page_size;

        return ['purchase' => $purchase, 'printPageSize' => $printPageSize];
    }

    public function createMethodContainer(object $codeGenerator): array
    {
        $data = [];
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $generalSettings = config('generalSettings');
        $invoicePrefix = $generalSettings['prefix__purchase_invoice_prefix'] ? $generalSettings['prefix__purchase_invoice_prefix'] : 'PI';
        $data['invoiceId'] = $codeGenerator->generateMonthAndTypeWise(table: 'purchases', column: 'invoice_id', typeColName: 'purchase_status', typeValue: PurchaseStatus::Purchase->value, prefix: $invoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

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

        $data['purchaseAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['warehouses'] = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        // $type = auth()->user()->can('customer_all') ? 'both' : 'supplier';
        $data['supplierAccounts'] = $this->accountService->customerAndSupplierAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId, sortingByGroupNumber: 'desc');

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): array
    {
        $restrictions = $this->purchaseService->restrictions($request);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $invoicePrefix = $generalSettings['prefix__purchase_invoice_prefix'] ? $generalSettings['prefix__purchase_invoice_prefix'] : 'PI';
        $paymentVoucherPrefix = $generalSettings['prefix__payment_voucher_prefix'] ? $generalSettings['prefix__payment_voucher_prefix'] : 'PV';
        $isEditProductPrice = $generalSettings['purchase__is_edit_pro_price'];

        $autoRepayPurchaseAndSalesReturn = isset($generalSettings['business_or_shop__auto_repayment_purchase_and_sales_return']) ? $generalSettings['business_or_shop__auto_repayment_purchase_and_sales_return'] : 0;

        $updateLastCreated = $this->purchaseService->purchaseByAnyConditions()->where('is_last_created', BooleanType::True->value)->where('branch_id', auth()->user()->branch_id)->select('id', 'is_last_created')->first();

        if ($updateLastCreated) {

            $updateLastCreated->is_last_created = BooleanType::False->value;
            $updateLastCreated->save();
        }

        $addPurchase = $this->purchaseService->addPurchase(request: $request, codeGenerator: $codeGenerator, invoicePrefix: $invoicePrefix);

        // Add Day Book entry for Purchase
        $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Purchase->value, date: $request->date, accountId: $request->supplier_account_id, transId: $addPurchase->id, amount: $request->total_purchase_amount, amountType: 'credit');

        // Add Purchase A/c Ledger Entry
        $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Purchase->value, date: $request->date, account_id: $request->purchase_account_id, trans_id: $addPurchase->id, amount: $request->purchase_ledger_amount, amount_type: 'debit');

        // Add supplier A/c ledger Entry For Purchase
        $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Purchase->value, account_id: $request->supplier_account_id, date: $request->date, trans_id: $addPurchase->id, amount: $request->total_purchase_amount, amount_type: 'credit');

        if ($request->purchase_tax_ac_id) {

            // Add Tax A/c ledger Entry For Purchase
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Purchase->value, account_id: $request->purchase_tax_ac_id, date: $request->date, trans_id: $addPurchase->id, amount: $request->purchase_tax_amount, amount_type: 'debit');
        }

        foreach ($request->product_ids as $index => $productId) {

            $addPurchaseProduct = $this->purchaseProductService->addPurchaseProduct(request: $request, purchaseId: $addPurchase->id, isEditProductPrice: $isEditProductPrice, index: $index);

            // Add Product Ledger Entry
            $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Purchase->value, date: $request->date, productId: $productId, transId: $addPurchaseProduct->id, rate: $addPurchaseProduct->net_unit_cost, quantityType: 'in', quantity: $addPurchaseProduct->quantity, subtotal: $addPurchaseProduct->line_total, variantId: $addPurchaseProduct->variant_id, warehouseId: (isset($request->warehouse_count) ? $request->warehouse_id : null));

            // purchase product tax will be go here
            if ($addPurchaseProduct->tax_ac_id) {

                // Add Tax A/c ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PurchaseProductTax->value, date: $request->date, account_id: $addPurchaseProduct->tax_ac_id, trans_id: $addPurchaseProduct->id, amount: ($addPurchaseProduct->unit_tax_amount * $addPurchaseProduct->quantity), amount_type: 'debit');
            }
        }

        if ($request->paying_amount > 0) {

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Payment->value, remarks: null, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount, purchaseRefId: $addPurchase->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->supplier_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

            // Add Day Book entry for Payment
            $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Payment->value, date: $request->date, accountId: $request->supplier_account_id, transId: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amountType: 'debit');

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherDebitDescription->id, accountId: $request->supplier_account_id, amount: $request->paying_amount, refIdColName: 'purchase_id', refIds: [$addPurchase->id]);

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->supplier_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->account_id);

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, note: $request->payment_note);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');
        }

        $purchase = $this->purchaseService->purchaseByAnyConditions(
            with: [
                'warehouse:id,warehouse_name,warehouse_code',
                'branch',
                'supplier',
                'admin:id,prefix,name,last_name',
                'purchaseProducts',
                'purchaseProducts.product',
                'purchaseProducts.product.warranty',
                'purchaseProducts.variant',
                'purchaseProducts.unit:id,code_name',
            ]
        )->where('id', $addPurchase->id)->first();

        if ($purchase->due > 0 && $autoRepayPurchaseAndSalesReturn == 1) {

            $this->accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->supplier_account_id, accountingVoucherType: AccountingVoucherType::Payment->value, refIdColName: 'purchase_id', purchase: $purchase);
        }

        // update main product and variant price
        foreach ($request->product_ids as $index => $productId) {

            $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $__xMargin = isset($request->profits) ? $request->profits[$index] : 0;
            $__selling_price = isset($request->selling_prices) ? $request->selling_prices[$index] : 0;

            $this->productService->updateProductAndVariantPrice(productId: $productId, variantId: $variantId, unitCostWithDiscount: $request->unit_costs_with_discount[$index], unitCostIncTax: $request->net_unit_costs[$index], profit: $__xMargin, sellingPrice: $__selling_price, isEditProductPrice: $isEditProductPrice, isLastEntry: $purchase->is_last_created);
        }

        foreach ($request->product_ids as $index => $productId) {

            $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

            $this->productStockService->adjustBranchAllStock($productId, $variantId, branchId: auth()->user()->branch_id);

            if (isset($request->warehouse_count) && !empty($request->warehouse_id)) {

                $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);
            }
        }

        // Add user Log
        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Purchase->value, dataObj: $purchase);

        $payingAmount = $request->paying_amount;
        $printPageSize = $request->print_page_size;

        return ['purchase' => $purchase, 'payingAmount' => $payingAmount, 'printPageSize' => $printPageSize];
    }

    public function editMethodContainer(int $id): array
    {
        $data = [];
        $purchase = $this->purchaseService->singlePurchase(id: $id, with: [
            'branch',
            'branch.parentBranch',
            'supplier',
            'supplier.group',
            'purchaseAccount:id,name',
            'purchaseProducts',
            'purchaseProducts.product',
            'purchaseProducts.product.warranty',
            'purchaseProducts.variant',
            'purchaseProducts.product.unit:id,name,code_name',
            'purchaseProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'purchaseProducts.unit:id,name,code_name,base_unit_multiplier',
        ]);

        abort_if(!$purchase, 404);

        $ownBranchIdOrParentBranchId = $purchase?->branch?->parent_branch_id ? $purchase?->branch?->parent_branch_id : $purchase->branch_id;

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $purchase->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['purchaseAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->where('accounts.branch_id', $purchase->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['warehouses'] = $this->warehouseService->warehouses()->where('branch_id', $purchase->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $purchase->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        // $type = auth()->user()->can('customer_all') ? 'both' : 'supplier';
        $data['supplierAccounts'] = $this->accountService->customerAndSupplierAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId, sortingByGroupNumber: 'desc');

        $data['purchase'] = $purchase;
        $data['ownBranchIdOrParentBranchId'] = $ownBranchIdOrParentBranchId;

        return $data;
    }

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array
    {
        $restrictions = $this->purchaseService->restrictions(request: $request, checkSupplierChangeRestriction: true, purchaseId: $id);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $paymentVoucherPrefix = $generalSettings['prefix__payment_voucher_prefix'] ? $generalSettings['prefix__payment_voucher_prefix'] : 'PV';
        $isEditProductPrice = $generalSettings['purchase__is_edit_pro_price'];
        $autoRepayPurchaseAndSalesReturn = isset($generalSettings['business_or_shop__auto_repayment_purchase_and_sales_return']) ? $generalSettings['business_or_shop__auto_repayment_purchase_and_sales_return'] : 0;

        $purchase = $this->purchaseService->singlePurchase(id: $id, with: ['purchaseProducts']);

        $storedCurrPurchaseAccountId = $purchase->purchase_account_id;
        $storedCurrSupplierAccountId = $purchase->supplier_account_id;
        $storedCurrPurchaseTaxAccountId = $purchase->purchase_tax_ac_id;
        $storedCurrentWarehouseId = $purchase->warehouse_id;
        $storePurchaseProducts = $purchase->purchaseProducts;

        $updatePurchase = $this->purchaseService->updatePurchase(request: $request, updatePurchase: $purchase);

        // Add Day Book entry for Purchase
        $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Purchase->value, date: $request->date, accountId: $request->supplier_account_id, transId: $updatePurchase->id, amount: $request->total_purchase_amount, amountType: 'credit');

        // Add Purchase A/c Ledger Entry
        $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Purchase->value, date: $request->date, account_id: $request->purchase_account_id, trans_id: $updatePurchase->id, amount: $request->purchase_ledger_amount, amount_type: 'debit', current_account_id: $storedCurrPurchaseAccountId, branch_id: $updatePurchase->branch_id);

        // Add supplier A/c ledger Entry For Purchase
        $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Purchase->value, account_id: $request->supplier_account_id, date: $request->date, trans_id: $updatePurchase->id, amount: $request->total_purchase_amount, amount_type: 'credit', current_account_id: $storedCurrSupplierAccountId, branch_id: $updatePurchase->branch_id);

        if ($request->purchase_tax_ac_id) {

            // Add Tax A/c ledger Entry For Purchase
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Purchase->value, account_id: $request->purchase_tax_ac_id, date: $request->date, trans_id: $updatePurchase->id, amount: $request->purchase_tax_amount, amount_type: 'debit', current_account_id: $storedCurrPurchaseTaxAccountId, branch_id: $updatePurchase->branch_id);
        } else {

            $this->accountLedgerService->deleteUnusedLedgerEntry(voucherType: AccountLedgerVoucherType::Purchase->value, transId: $updatePurchase->id, accountId: $storedCurrPurchaseTaxAccountId);
        }

        $index = 0;
        foreach ($request->product_ids as $productId) {

            $updatePurchaseProduct = $this->purchaseProductService->updatePurchaseProduct(request: $request, purchaseId: $updatePurchase->id, isEditProductPrice: $isEditProductPrice, index: $index);

            // Add Product Ledger Entry
            $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Purchase->value, date: $request->date, productId: $productId, transId: $updatePurchaseProduct->id, rate: $updatePurchaseProduct->net_unit_cost, quantityType: 'in', quantity: $updatePurchaseProduct->quantity, subtotal: $updatePurchaseProduct->line_total, variantId: $updatePurchaseProduct->variant_id, warehouseId: (isset($request->warehouse_count) ? $request->warehouse_id : null), currentWarehouseId: $storedCurrentWarehouseId, branchId: $updatePurchase->branch_id);

            // purchase product tax will be go here
            if ($updatePurchaseProduct->tax_ac_id) {

                // Add Tax A/c ledger Entry
                $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PurchaseProductTax->value, date: $request->date, account_id: $updatePurchaseProduct->tax_ac_id, trans_id: $updatePurchaseProduct->id, amount: ($updatePurchaseProduct->unit_tax_amount * $updatePurchaseProduct->quantity), amount_type: 'debit', current_account_id: $updatePurchaseProduct->current_tax_ac_id, branch_id: $updatePurchase->branch_id);
            } else {

                $this->accountLedgerService->deleteUnusedLedgerEntry(voucherType: AccountLedgerVoucherType::PurchaseProductTax->value, transId: $updatePurchaseProduct->id, accountId: $updatePurchaseProduct->current_tax_ac_id);
            }

            $index++;
        }

        if ($request->paying_amount > 0) {

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Payment->value, remarks: null, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount, purchaseRefId: $updatePurchase->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->supplier_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

            // Add Day Book entry for Payment
            $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Payment->value, date: $request->date, accountId: $request->supplier_account_id, transId: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amountType: 'debit');

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherDebitDescription->id, accountId: $request->supplier_account_id, amount: $request->paying_amount, refIdColName: 'purchase_id', refIds: [$updatePurchase->id], branchId: $updatePurchase->branch_id);

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->supplier_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->account_id);

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, note: $request->payment_note);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');
        }

        $purchase = $this->purchaseService->purchaseByAnyConditions(with: ['purchaseProducts'])->where('id', $updatePurchase->id)->first();

        $adjustedPurchase = $this->purchaseService->adjustPurchaseInvoiceAmounts(purchase: $purchase);

        if ($purchase->due > 0 && $autoRepayPurchaseAndSalesReturn == 1) {

            $this->accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->supplier_account_id, accountingVoucherType: AccountingVoucherType::Payment->value, refIdColName: 'purchase_id', purchase: $adjustedPurchase);
        }

        $deletedUnusedPurchaseProducts = $purchase->purchaseProducts->where('delete_in_update', BooleanType::True->value);
        if (count($deletedUnusedPurchaseProducts) > 0) {

            foreach ($deletedUnusedPurchaseProducts as $deletedPurchaseProduct) {

                $deletedPurchaseProduct->delete();

                // Adjust deleted product stock
                $this->productStockService->adjustMainProductAndVariantStock($deletedPurchaseProduct->product_id, $deletedPurchaseProduct->variant_id);
                $this->productStockService->adjustBranchAllStock(productId: $deletedPurchaseProduct->product_id, variantId: $deletedPurchaseProduct->variant_id, branchId: $purchase->branch_id);

                if (isset($storedCurrentWarehouseId)) {

                    $this->productStockService->adjustWarehouseStock(productId: $deletedPurchaseProduct->product_id, variantId: $deletedPurchaseProduct->variant_id, warehouseId: $storedCurrentWarehouseId);
                } else {

                    $this->productStockService->adjustBranchStock(productId: $deletedPurchaseProduct->product_id, variantId: $deletedPurchaseProduct->variant_id, branchId: $purchase->branch_id);
                }
            }
        }

        foreach ($request->product_ids as $index => $productId) {

            $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

            $this->productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: $updatePurchase->branch_id);

            if (isset($request->warehouse_count) && !empty($request->warehouse_id)) {

                $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock(productId: $productId, variantId: $variantId, branchId: $updatePurchase->branch_id);
            }
        }

        if (isset($request->warehouse_count) && $storedCurrentWarehouseId && $request->warehouse_id != $storedCurrentWarehouseId) {

            foreach ($storePurchaseProducts as $purchaseProduct) {

                $this->productStockService->adjustWarehouseStock(productId: $purchaseProduct->product_id, variantId: $purchaseProduct->variant_id, warehouseId: $storedCurrentWarehouseId);
            }
        }

        // update main product and variant price
        foreach ($request->product_ids as $index => $productId) {

            $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $__xMargin = isset($request->profits) ? $request->profits[$index] : 0;
            $__selling_price = isset($request->selling_prices) ? $request->selling_prices[$index] : 0;

            $this->productService->updateProductAndVariantPrice(productId: $productId, variantId: $variantId, unitCostWithDiscount: $request->unit_costs_with_discount[$index], unitCostIncTax: $request->net_unit_costs[$index], profit: $__xMargin, sellingPrice: $__selling_price, isEditProductPrice: $isEditProductPrice, isLastEntry: $purchase->is_last_created);
        }

        // Add user Log
        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Purchase->value, dataObj: $purchase);

        return null;
    }

    public function deleteMethodContainer(int $id): ?array
    {
        $deletePurchase = $this->purchaseService->deletePurchase(id: $id);

        if (isset($deletePurchase['pass']) && $deletePurchase['pass'] == false) {

            return ['pass' => false, 'msg' => $deletePurchase['msg']];
        }

        foreach ($deletePurchase->purchaseProducts as $purchaseProduct) {

            $variantId = $purchaseProduct->variant_id ? $purchaseProduct->variant_id : null;
            $this->productStockService->adjustMainProductAndVariantStock(productId: $purchaseProduct->product_id, variantId: $variantId);

            $this->productStockService->adjustBranchAllStock(productId: $purchaseProduct->product_id, variantId: $variantId, branchId: $deletePurchase->branch_id);

            if ($deletePurchase->warehouse_id) {

                $this->productStockService->adjustWarehouseStock(productId: $purchaseProduct->product_id, variantId: $variantId, warehouseId: $deletePurchase->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock(productId: $purchaseProduct->product_id, variantId: $variantId, branchId: $deletePurchase->branch_id);
            }
        }

        // Add user Log
        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Purchase->value, dataObj: $deletePurchase);

        return null;
    }

    public function searchPurchasesByInvoiceIdMethodContainer(int $keyWord): array|object
    {
        $purchases = DB::table('purchases')
            ->leftJoin('accounts', 'purchases.supplier_account_id', 'accounts.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->where('purchases.invoice_id', 'like', "%{$keyWord}%")
            ->where('purchases.branch_id', auth()->user()->branch_id)
            ->where('purchases.purchase_status', PurchaseStatus::Purchase->value)
            ->select(
                'purchases.id as purchase_id',
                'purchases.warehouse_id',
                'purchases.invoice_id as p_invoice_id',
                'purchases.supplier_account_id',
                'warehouses.warehouse_name',
                'accounts.name as supplier_name',
                'accounts.phone as supplier_phone',
                'account_groups.default_balance_type',
            )->limit(35)->get();

        if (count($purchases) > 0) {

            return view('search_results_view.purchase_invoice_search_result_list', compact('purchases'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }

    public function purchaseInvoiceIdMethodContainer(object $codeGenerator): string
    {
        $generalSettings = config('generalSettings');
        $invoicePrefix = $generalSettings['prefix__purchase_invoice_prefix'] ? $generalSettings['prefix__purchase_invoice_prefix'] : 'PI';

        return $codeGenerator->generateMonthAndTypeWise(table: 'purchases', column: 'invoice_id', typeColName: 'purchase_status', typeValue: PurchaseStatus::Purchase->value, prefix: $invoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
    }
}
