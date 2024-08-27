<?php

namespace App\Services\Sales\MethodContainerServices;

use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Enums\DayBookVoucherType;
use App\Services\Sales\SaleService;
use App\Enums\AccountingVoucherType;
use App\Services\Sales\DraftService;
use App\Services\Branches\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Sales\SalesOrderService;
use App\Services\Setups\WarehouseService;
use App\Services\Sales\SaleProductService;
use App\Services\Sales\DraftProductService;
use App\Services\Products\PriceGroupService;
use App\Services\Products\StockChainService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Products\ManagePriceGroupService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Interfaces\Sales\DraftControllerMethodContainersInterface;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class DraftControllerMethodContainersService implements DraftControllerMethodContainersInterface
{
    public function __construct(
        private DraftService $draftService,
        private SaleService $saleService,
        private DayBookService $dayBookService,
        private DraftProductService $draftProductService,
        private SaleProductService $saleProductService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private AccountLedgerService $accountLedgerService,
        private PaymentMethodService $paymentMethodService,
        private BranchService $branchService,
        private ProductStockService $productStockService,
        private ProductLedgerService $productLedgerService,
        private PriceGroupService $priceGroupService,
        private PurchaseProductService $purchaseProductService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private WarehouseService $warehouseService,
        private ManagePriceGroupService $managePriceGroupService,
        private StockChainService $stockChainService,
        private SalesOrderService $salesOrderService,
        private UserActivityLogService $userActivityLogService,
    ) {}

    public function indexMethodContainer(object $request): object|array
    {
        $data = [];
        if ($request->ajax()) {

            return $this->draftService->draftListTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $data['customerAccounts'] = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return $data;
    }

    public function showMethodContainer(int $id): array
    {
        $data = [];
        $draft = $this->draftService->singleDraft(id: $id, with: [
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

        $data['customerCopySaleProducts'] = $this->saleProductService->customerCopySaleProducts(saleId: $draft->id);
        $data['draft'] = $draft;

        return $data;
    }

    public function editMethodContainer(int $id): array
    {
        $draft = $this->draftService->singleDraft(id: $id, with: [
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

        $data['branchName'] = $this->branchService->branchName($draft);

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $draft->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['warehouses'] = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['saleAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', $draft->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $draft->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['customerAccounts'] = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $data['priceGroups'] = $this->priceGroupService->priceGroups()->get(['id', 'name']);
        $data['priceGroupProducts'] = $this->managePriceGroupService->priceGroupProducts();
        $data['draft'] = $draft;

        return $data;
    }

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array
    {
        $restrictions = $this->saleService->restrictions(request: $request, accountService: $this->accountService);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $invoicePrefix = $generalSettings['prefix__sales_invoice_prefix'] ? $generalSettings['prefix__sales_invoice_prefix'] : 'SI';
        $quotationPrefix = $generalSettings['prefix__quotation_prefix'] ? $generalSettings['prefix__quotation_prefix'] : 'Q';
        $salesOrderPrefix = $generalSettings['prefix__sales_order_prefix'] ? $generalSettings['prefix__sales_order_prefix'] : 'SO';
        $receiptVoucherPrefix = $generalSettings['prefix__receipt_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'RV';
        $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        $draft = $this->draftService->singleDraft(id: $id, with: ['saleProducts']);

        $updateDraft = $this->draftService->updateDraft(request: $request, updateDraft: $draft, codeGenerator: $codeGenerator, salesOrderPrefix: $salesOrderPrefix, invoicePrefix: $invoicePrefix, quotationPrefix: $quotationPrefix);

        if ($request->status == SaleStatus::Final->value || $request->status == SaleStatus::Order->value) {

            $dayBookVoucherType = $request->status == SaleStatus::Final->value ? DayBookVoucherType::Sales->value : DayBookVoucherType::SalesOrder->value;

            // Add Day Book entry for Final Sale or Sales Order
            $this->dayBookService->addDayBook(voucherTypeId: $dayBookVoucherType, date: $request->date, accountId: $request->customer_account_id, transId: $updateDraft->id, amount: $request->total_invoice_amount, amountType: 'debit');
        }

        if ($request->status == SaleStatus::Final->value) {

            // Add Sale A/c Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, date: $request->date, account_id: $request->sale_account_id, trans_id: $updateDraft->id, amount: $request->sales_ledger_amount, amount_type: 'credit');

            // Add supplier A/c ledger Entry For Sales
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->customer_account_id, date: $request->date, trans_id: $updateDraft->id, amount: $request->total_invoice_amount, amount_type: 'debit');

            if ($request->sale_tax_ac_id) {

                // Add Tax A/c ledger Entry For Sales
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->sale_tax_ac_id, date: $request->date, trans_id: $updateDraft->id, amount: $request->order_tax_amount, amount_type: 'credit');
            }
        }

        foreach ($request->product_ids as $index => $productId) {

            $updateDraftProduct = $this->draftProductService->updateDraftProduct(request: $request, draft: $updateDraft, index: $index);

            if ($request->status == SaleStatus::Final->value) {

                // Add Product Ledger Entry
                $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Sales->value, date: $request->date, productId: $productId, transId: $updateDraftProduct->id, rate: $updateDraftProduct->unit_price_inc_tax, quantityType: 'out', quantity: $updateDraftProduct->quantity, subtotal: $updateDraftProduct->subtotal, variantId: $updateDraftProduct->variant_id, warehouseId: (isset($request->warehouse_ids[$index]) ? $request->warehouse_ids[$index] : null));

                if ($updateDraftProduct->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SaleProductTax->value, date: $request->date, account_id: $updateDraftProduct->tax_ac_id, trans_id: $updateDraftProduct->id, amount: ($updateDraftProduct->unit_tax_amount * $updateDraftProduct->quantity), amount_type: 'credit');
                }
            }
        }

        if (($request->status == SaleStatus::Final->value || $request->status == SaleStatus::Order->value) && $request->received_amount > 0) {

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, saleRefId: $updateDraft->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount);

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount, note: $request->payment_note);

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: [$updateDraft->id]);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        $draft = $this->draftService->singleDraft(id: $id, with: ['saleProducts']);

        if ($draft->due > 0 && $draft->status == SaleStatus::Final->value) {

            $this->accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->customer_account_id, accountingVoucherType: AccountingVoucherType::Receipt->value, refIdColName: 'sale_id', sale: $draft);
        }

        if ($request->status == SaleStatus::Final->value) {

            foreach ($request->product_ids as $__index => $productId) {

                $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

                $this->productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);

                if (isset($request->warehouse_ids[$__index])) {

                    $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_ids[$__index]);
                } else {

                    $this->productStockService->adjustBranchStock($productId, $variantId, branchId: auth()->user()->branch_id);
                }

                $this->stockChainService->addStockChain(sale: $draft, stockAccountingMethod: $stockAccountingMethod);
            }
        }

        $deletedUnusedDraftProducts = $draft->saleProducts()->where('is_delete_in_update', BooleanType::True->value)->get();

        if (count($deletedUnusedDraftProducts) > 0) {

            foreach ($deletedUnusedDraftProducts as $deletedUnusedDraftProduct) {

                $deletedUnusedDraftProduct->delete();
            }
        }

        $subjectType = '';
        if ($request->status == SaleStatus::Final->value) {

            $subjectType = UserActivityLogSubjectType::Sales->value;
        } elseif ($request->status == SaleStatus::Order->value) {

            $subjectType = UserActivityLogSubjectType::SalesOrder->value;
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $subjectType = UserActivityLogSubjectType::Quotation->value;
        }

        if ($subjectType) {

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: $subjectType, dataObj: $draft);
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Draft->value, dataObj: $draft);

        return null;
    }

    public function deleteMethodContainer(int $id): ?array
    {
        $deleteSale = $this->saleService->deleteSale($id);

        if (isset($deleteSale['pass']) && $deleteSale['pass'] == false) {

            return ['pass' => false, 'msg' => $deleteSale['msg']];
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Draft->value, dataObj: $deleteSale);

        return null;
    }
}
