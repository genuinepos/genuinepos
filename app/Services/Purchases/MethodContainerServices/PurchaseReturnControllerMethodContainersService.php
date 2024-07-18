<?php

namespace App\Services\Purchases\MethodContainerServices;

use App\Enums\BooleanType;
use App\Enums\DayBookVoucherType;
use App\Enums\AccountingVoucherType;
use App\Services\Setups\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\WarehouseService;
use App\Services\Purchases\PurchaseService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Purchases\PurchaseReturnService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Purchases\PurchaseReturnProductService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Interfaces\Purchases\PurchaseReturnControllerMethodContainersInterface;

class PurchaseReturnControllerMethodContainersService implements PurchaseReturnControllerMethodContainersInterface
{
    public function __construct(
        private PurchaseReturnService $purchaseReturnService,
        private PurchaseReturnProductService $purchaseReturnProductService,
        private PurchaseService $purchaseService,
        private UserActivityLogService $userActivityLogService,
        private PaymentMethodService $paymentMethodService,
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
    ) {
    }

    public function indexMethodContainer(object $request): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->purchaseReturnService->purchaseReturnsTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $data['supplierAccounts'] = $this->accountService->customerAndSupplierAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId, sortingByGroupNumber: 'desc');

        return $data;
    }

    public function showMethodContainer($id): ?array
    {
        $data = [];
        $data['return'] = $this->purchaseReturnService->singlePurchaseReturn(id: $id, with: [
            'purchase',
            'branch',
            'branch.parentBranch',
            'supplier:id,name,phone,address,account_group_id',
            'supplier.group',
            'createdBy:id,prefix,name,last_name',
            'purchaseReturnProducts',
            'purchaseReturnProducts.product',
            'purchaseReturnProducts.variant',
            'purchaseReturnProducts.branch:id,name,branch_code,area_name,parent_branch_id',
            'purchaseReturnProducts.branch.parentBranch:id,name,branch_code,area_name',
            'purchaseReturnProducts.warehouse:id,warehouse_name,warehouse_code',
            'purchaseReturnProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'purchaseReturnProducts.unit.baseUnit:id,base_unit_id,code_name',

            'references:id,voucher_description_id,purchase_return_id,amount',
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

    public function printMethodContainer(object $request, int $id): ?array
    {
        $data = [];
        $data['return'] = $this->purchaseReturnService->singlePurchaseReturn(id: $id, with: [
            'purchase',
            'branch',
            'branch.parentBranch',
            'supplier:id,name,phone,address,account_group_id',
            'supplier.group',
            'createdBy:id,prefix,name,last_name',
            'purchaseReturnProducts',
            'purchaseReturnProducts.product',
            'purchaseReturnProducts.variant',
            'purchaseReturnProducts.branch:id,name,branch_code,area_name,parent_branch_id',
            'purchaseReturnProducts.branch.parentBranch:id,name,branch_code,area_name',
            'purchaseReturnProducts.warehouse:id,warehouse_name,warehouse_code',
            'purchaseReturnProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'purchaseReturnProducts.unit.baseUnit:id,base_unit_id,code_name',
        ]);

        $data['printPageSize'] = $request->print_page_size;

        return $data;
    }

    public function createMethodContainer(object $codeGenerator): array
    {
        $data = [];
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $generalSettings = config('generalSettings');
        $voucherPrefix = $generalSettings['prefix__purchase_return_prefix'] ? $generalSettings['prefix__purchase_return_prefix'] : 'PR';

        $data['voucherNo'] = $codeGenerator->generateMonthWise(table: 'purchase_returns', column: 'voucher_no', prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

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
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['supplierAccounts'] = $this->accountService->customerAndSupplierAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId, sortingByGroupNumber: 'desc');

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): array
    {
        $restrictions = $this->purchaseReturnService->restrictions($request);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $purchaseReturnVoucherPrefix = $generalSettings['prefix__purchase_return_prefix'] ? $generalSettings['prefix__purchase_return_prefix'] : 'PR';
        $receiptVoucherPrefix = $generalSettings['prefix__receipt_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'RV';

        $addReturn = $this->purchaseReturnService->addPurchaseReturn(request: $request, voucherPrefix: $purchaseReturnVoucherPrefix, codeGenerator: $codeGenerator);

        $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::PurchaseReturn->value, date: $request->date, accountId: $request->supplier_account_id, transId: $addReturn->id, amount: $request->total_return_amount, amountType: 'debit');

        // Add Purchase A/c Ledger Entry
        $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PurchaseReturn->value, date: $request->date, account_id: $request->purchase_account_id, trans_id: $addReturn->id, amount: $request->purchase_ledger_amount, amount_type: 'credit');

        // Add supplier A/c ledger Entry For Purchase
        $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PurchaseReturn->value, account_id: $request->supplier_account_id, date: $request->date, trans_id: $addReturn->id, amount: $request->total_return_amount, amount_type: 'debit');

        if ($request->return_tax_ac_id) {

            // Add Tax A/c ledger Entry For Purchase
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PurchaseReturn->value, account_id: $request->return_tax_ac_id, date: $request->date, trans_id: $addReturn->id, amount: $request->return_tax_amount, amount_type: 'credit');
        }

        $index = 0;
        foreach ($request->product_ids as $productId) {

            $addPurchaseReturnProduct = $this->purchaseReturnProductService->addPurchaseReturnProduct(request: $request, purchaseReturnId: $addReturn->id, index: $index);

            // Add Product Ledger Entry
            $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::PurchaseReturn->value, date: $request->date, productId: $productId, transId: $addPurchaseReturnProduct->id, rate: $addPurchaseReturnProduct->unit_cost_inc_tax, quantityType: 'out', quantity: $addPurchaseReturnProduct->return_qty, subtotal: $addPurchaseReturnProduct->return_subtotal, variantId: $addPurchaseReturnProduct->variant_id, warehouseId: ($addPurchaseReturnProduct->warehouse_id ? $addPurchaseReturnProduct->warehouse_id : null));

            // purchase product tax will be go here
            if ($addPurchaseReturnProduct->tax_ac_id) {

                // Add Tax A/c ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PurchaseReturnProductTax->value, date: $request->date, account_id: $addPurchaseReturnProduct->tax_ac_id, trans_id: $addPurchaseReturnProduct->id, amount: ($addPurchaseReturnProduct->unit_tax_amount * $addPurchaseReturnProduct->return_qty), amount_type: 'credit');
            }

            $index++;
        }

        if ($request->received_amount > 0) {

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->receipt_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, purchaseReturnRefId: $addReturn->id);

            // Add Payment Description Credit Entry
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: null, amountType: 'dr', amount: $request->received_amount, note: null);

            //Add debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Credit Account Accounting voucher Description
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->supplier_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->received_amount);

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->supplier_account_id, amount: $request->received_amount, refIdColName: 'purchase_return_id', refIds: [$addReturn->id]);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->supplier_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->supplier_account_id);
        }

        $__index = 0;
        foreach ($request->product_ids as $productId) {

            $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
            $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

            $this->productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);

            if (isset($request->warehouse_ids[$__index])) {

                $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_ids[$__index]);
            } else {

                $this->productStockService->adjustBranchStock($productId, $variantId, branchId: auth()->user()->branch_id);
            }

            $__index++;
        }

        if ($request->purchase_id) {

            $purchase = $this->purchaseService->singlePurchase(id: $request->purchase_id);
            $this->purchaseService->adjustPurchaseInvoiceAmounts(purchase: $purchase);
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::PurchaseReturn->value, dataObj: $addReturn);

        $printPageSize = $request->print_page_size;
        $receivedAmount = $request->received_amount;
        $return = $this->purchaseReturnService->singlePurchaseReturn(id: $addReturn->id, with: [
            'purchase',
            'branch',
            'branch.parentBranch',
            'supplier',
            'supplier.group',
            'purchaseReturnProducts',
            'purchaseReturnProducts.product',
            'purchaseReturnProducts.variant',
            'purchaseReturnProducts.unit',
        ]);

        return ['printPageSize' => $printPageSize, 'receivedAmount' => $receivedAmount, 'return' => $return];
    }

    public function editMethodContainer(int $id): array
    {
        $data = [];
        $return = $this->purchaseReturnService->singlePurchaseReturn(id: $id, with: [
            'branch',
            'branch.parentBranch',
            'supplier:id,name,phone,address,account_group_id',
            'supplier.group',
            'createdBy:id,prefix,name,last_name',
            'purchaseReturnProducts',
            'purchaseReturnProducts.purchaseProduct',
            'purchaseReturnProducts.product',
            'purchaseReturnProducts.variant',
            'purchaseReturnProducts.branch:id,name,branch_code,area_name,parent_branch_id',
            'purchaseReturnProducts.branch.parentBranch:id,name,branch_code,area_name',
            'purchaseReturnProducts.warehouse:id,warehouse_name,warehouse_code',
            'purchaseReturnProducts.product.unit:id,name,code_name',
            'purchaseReturnProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'purchaseReturnProducts.unit:id,name,code_name,base_unit_multiplier',
        ]);

        $ownBranchIdOrParentBranchId = $return?->branch?->parent_branch_id ? $return?->branch?->parent_branch_id : $return?->branch_id;

        $data['branchName'] = $this->branchService->branchName(transObject: $return);

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $return->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['purchaseAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->where('accounts.branch_id', $return->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['warehouses'] = $this->warehouseService->warehouses()->where('branch_id', $return->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $return->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['supplierAccounts'] = $this->accountService->customerAndSupplierAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId, sortingByGroupNumber: 'desc');

        $data['return'] = $return;

        return $data;
    }

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array
    {
        $restrictions = $this->purchaseReturnService->restrictions(request: $request, checkSupplierChangeRestriction: true, purchaseReturnId: $id);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $receiptVoucherPrefix = $generalSettings['prefix__receipt_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'RV';

        $return = $this->purchaseReturnService->singlePurchaseReturn(id: $id, with: ['purchaseReturnProducts']);

        $storedCurrParentPurchaseId = $return->purchase_id;
        $storedCurrPurchaseAccountId = $return->purchase_account_id;
        $storedCurrSupplierAccountId = $return->supplier_account_id;
        $storedCurrReturnTaxAccountId = $return->return_tax_ac_id;

        $updateReturn = $this->purchaseReturnService->updatePurchaseReturn(request: $request, updatePurchaseReturn: $return);

        // Update Day Book entry for Purchase
        $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::PurchaseReturn->value, date: $updateReturn->date, accountId: $updateReturn->supplier_account_id, transId: $updateReturn->id, amount: $updateReturn->total_return_amount, amountType: 'debit');

        // Update supplier A/c ledger Entry For Purchase return
        $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PurchaseReturn->value, account_id: $updateReturn->supplier_account_id, date: $updateReturn->date, trans_id: $updateReturn->id, amount: $updateReturn->total_return_amount, amount_type: 'debit', branch_id: $updateReturn->branch_id, current_account_id: $storedCurrSupplierAccountId);

        // Update Purchase A/c Ledger Entry
        $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PurchaseReturn->value, date: $updateReturn->date, account_id: $updateReturn->purchase_account_id, trans_id: $updateReturn->id, amount: $request->purchase_ledger_amount, amount_type: 'credit', branch_id: $updateReturn->branch_id, current_account_id: $storedCurrPurchaseAccountId);

        if ($request->return_tax_ac_id) {

            // Update Tax A/c ledger Entry For Purchase
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PurchaseReturn->value, account_id: $updateReturn->return_tax_ac_id, date: $updateReturn->date, trans_id: $updateReturn->id, amount: $updateReturn->return_tax_amount, amount_type: 'credit', branch_id: $updateReturn->branch_id, current_account_id: $storedCurrReturnTaxAccountId);
        } else {

            $this->accountLedgerService->deleteUnusedLedgerEntry(voucherType: AccountLedgerVoucherType::PurchaseReturn->value, transId: $updateReturn->id, accountId: $storedCurrReturnTaxAccountId);
        }

        $index = 0;
        foreach ($request->product_ids as $productId) {

            $updatePurchaseReturnProduct = $this->purchaseReturnProductService->updatePurchaseReturnProduct(request: $request, purchaseReturnId: $updateReturn->id, index: $index);

            // Update Product Ledger Entry
            $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::PurchaseReturn->value, date: $request->date, productId: $productId, transId: $updatePurchaseReturnProduct->id, rate: $updatePurchaseReturnProduct->unit_cost_inc_tax, quantityType: 'out', quantity: $updatePurchaseReturnProduct->return_qty, subtotal: $updatePurchaseReturnProduct->return_subtotal, variantId: $updatePurchaseReturnProduct->variant_id, warehouseId: ($updatePurchaseReturnProduct->warehouse_id ? $updatePurchaseReturnProduct->warehouse_id : null), currentWarehouseId: $updatePurchaseReturnProduct->current_warehouse_id, branchId: $updateReturn->branch_id);

            if ($updatePurchaseReturnProduct->tax_ac_id) {

                // Update Tax A/c ledger Entry
                $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PurchaseReturnProductTax->value, date: $request->date, account_id: $updatePurchaseReturnProduct->tax_ac_id, trans_id: $updatePurchaseReturnProduct->id, amount: ($updatePurchaseReturnProduct->unit_tax_amount * $updatePurchaseReturnProduct->return_qty), amount_type: 'credit', branch_id: $updateReturn->branch_id, current_account_id: $updatePurchaseReturnProduct->current_tax_ac_id);
            } else {

                $this->accountLedgerService->deleteUnusedLedgerEntry(voucherType: AccountLedgerVoucherType::PurchaseReturnProductTax->value, transId: $updatePurchaseReturnProduct->id, accountId: $updatePurchaseReturnProduct->current_tax_ac_id);
            }

            $index++;
        }

        if ($request->received_amount > 0) {

            $receiptDate = $request->receipt_date ? $request->receipt_date : $request->date;

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $receiptDate, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->receipt_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, purchaseReturnRefId: $updateReturn->id);

            // Add Accounting Voucher Description Credit Entry
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: null, amountType: 'dr', amount: $request->received_amount, note: null);

            //Add debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $receiptDate, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Credit Account Accounting voucher Description
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->supplier_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->received_amount);

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->supplier_account_id, amount: $request->received_amount, refIdColName: 'purchase_return_id', refIds: [$updateReturn->id]);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $receiptDate, account_id: $request->supplier_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        $__index = 0;
        foreach ($request->product_ids as $productId) {

            $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
            $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

            $this->productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: $updateReturn->branch_id);

            if (isset($request->warehouse_ids[$__index])) {

                $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_ids[$__index]);
            } else {

                $this->productStockService->adjustBranchStock($productId, $variantId, branchId: $updateReturn->branch_id);
            }

            $__index++;
        }

        $deletedUnusedPurchaseReturnProducts = $this->purchaseReturnProductService->purchaseReturnProducts()->where('purchase_return_id', $updateReturn->id)->where('is_delete_in_update', BooleanType::True->value)->get();
        if (count($deletedUnusedPurchaseReturnProducts) > 0) {

            foreach ($deletedUnusedPurchaseReturnProducts as $deletedUnusedPurchaseReturnProduct) {

                $deletedUnusedPurchaseReturnProduct->delete();

                // Adjust deleted product stock
                $this->productStockService->adjustMainProductAndVariantStock($deletedUnusedPurchaseReturnProduct->product_id, $deletedUnusedPurchaseReturnProduct->variant_id);

                $this->productStockService->adjustBranchAllStock(productId: $deletedUnusedPurchaseReturnProduct->product_id, variantId: $deletedUnusedPurchaseReturnProduct->variant_id, branchId: $updateReturn->branch_id);

                if (isset($deletedUnusedPurchaseReturnProduct->warehouse_id)) {

                    $this->productStockService->adjustWarehouseStock($deletedUnusedPurchaseReturnProduct->product_id, $deletedUnusedPurchaseReturnProduct->variant_id, $deletedUnusedPurchaseReturnProduct->warehouse_id);
                } else {

                    $this->productStockService->adjustBranchStock($deletedUnusedPurchaseReturnProduct->product_id, $deletedUnusedPurchaseReturnProduct->variant_id, $updateReturn->branch_id);
                }
            }
        }

        if ($request->purchase_id) {

            $purchase = $this->purchaseService->singlePurchase(id: $request->purchase_id);
            $this->purchaseService->adjustPurchaseInvoiceAmounts($purchase);
        }

        if ($storedCurrParentPurchaseId) {

            $purchase = $this->purchaseService->singlePurchase(id: $storedCurrParentPurchaseId);
            $this->purchaseService->adjustPurchaseInvoiceAmounts($purchase);
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::PurchaseReturn->value, dataObj: $updateReturn);

        return null;
    }

    public function deleteMethodContainer(int $id): ?array
    {
        $deletePurchaseReturn = $this->purchaseReturnService->deletePurchaseReturn(id: $id);

        if (isset($deletePurchaseReturn['pass']) && $deletePurchaseReturn['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        foreach ($deletePurchaseReturn->purchaseReturnProducts as $returnProduct) {

            $this->productStockService->adjustMainProductAndVariantStock($returnProduct->product_id, $returnProduct->variant_id);

            $this->productStockService->adjustBranchAllStock($returnProduct->product_id, $returnProduct->variant_id, $deletePurchaseReturn->branch_id);

            if ($returnProduct->warehouse_id) {

                $this->productStockService->adjustWarehouseStock($returnProduct->product_id, $returnProduct->variant_id, $returnProduct->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock($returnProduct->product_id, $returnProduct->variant_id, $deletePurchaseReturn->branch_id);
            }
        }

        if ($deletePurchaseReturn->purchase) {

            $this->purchaseService->adjustPurchaseInvoiceAmounts($deletePurchaseReturn->purchase);
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::PurchaseReturn->value, dataObj: $deletePurchaseReturn);

        return null;
    }

    public function voucherNoMethodContainer(object $codeGenerator): string
    {
        $generalSettings = config('generalSettings');
        $voucherPrefix = $generalSettings['prefix__purchase_return_prefix'] ? $generalSettings['prefix__purchase_return_prefix'] : 'PR';

        return $codeGenerator->generateMonthWise(table: 'purchase_returns', column: 'voucher_no', prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
    }
}
