<?php

namespace App\Services\Purchases\MethodContainerServices;

use App\Enums\BooleanType;
use App\Enums\PurchaseStatus;
use App\Enums\DayBookVoucherType;
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
use App\Services\Accounts\AccountBalanceService;
use App\Services\Purchases\PurchaseOrderService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Purchases\PurchaseOrderProductService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Interfaces\Purchases\PurchaseOrderToInvoiceControllerMethodContainersInterface;

class PurchaseOrderToInvoiceControllerMethodContainersService implements PurchaseOrderToInvoiceControllerMethodContainersInterface
{
    public function __construct(
        private PurchaseService $purchaseService,
        private PurchaseOrderService $purchaseOrderService,
        private PurchaseOrderProductService $purchaseOrderProductService,
        private PurchaseProductService $purchaseProductService,
        private UserActivityLogService $userActivityLogService,
        private PaymentMethodService $paymentMethodService,
        private AccountService $accountService,
        private AccountBalanceService $accountBalanceService,
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

    public function createMethodContainer(object $codeGenerator, ?int $id = null): array
    {
        $data = [];
        $order = null;
        if (isset($id)) {

            $order = $this->purchaseOrderService->singlePurchaseOrder(id: $id, with: ['supplier', 'supplier.group', 'purchaseOrderProducts']);
        }

        $generalSettings = config('generalSettings');
        $invoicePrefix = $generalSettings['prefix__purchase_invoice_prefix'] ? $generalSettings['prefix__purchase_invoice_prefix'] : 'PI';
        $data['invoiceId'] = $codeGenerator->generateMonthAndTypeWise(table: 'purchases', column: 'invoice_id', typeColName: 'purchase_status', typeValue: PurchaseStatus::Purchase->value, prefix: $invoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

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

        $data['accountBalance'] = $this->accountBalanceService->accountBalance(accountId: $order?->supplier_account_id);

        $data['order'] = $order;

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

            if (isset($addPurchaseProduct->purchase_order_product_id)) {

                $this->purchaseOrderProductService->adjustPurchaseOrderProductPendingAndReceiveQty(purchaseOrderProductId: $addPurchaseProduct->purchase_order_product_id, productId: $addPurchaseProduct->product_id, variantId: $addPurchaseProduct->variant_id);
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
                'purchaseOrder:id,invoice_id',
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

        if ($purchase->due > 0) {

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

            if (isset($request->warehouse_count)) {

                $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);
            }
        }

        // Add user Log
        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Purchase->value, dataObj: $purchase);

        if (isset($purchase->purchase_order_id)) {

            $this->purchaseOrderService->updatePoReceivingStatus(purchaseOrderId: $purchase->purchase_order_id);
        }

        $payingAmount = $request->paying_amount;
        $printPageSize = $request->print_page_size;

        return ['purchase' => $purchase, 'payingAmount' => $payingAmount, 'printPageSize' => $printPageSize];
    }
}
