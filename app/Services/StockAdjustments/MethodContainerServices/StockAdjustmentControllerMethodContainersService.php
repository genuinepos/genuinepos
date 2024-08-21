<?php

namespace App\Services\StockAdjustments\MethodContainerServices;

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
use App\Services\Products\StockChainService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\StockAdjustments\StockAdjustmentService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\StockAdjustments\StockAdjustmentProductService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Interfaces\StockAdjustments\StockAdjustmentControllerMethodContainersInterface;

class StockAdjustmentControllerMethodContainersService implements StockAdjustmentControllerMethodContainersInterface
{
    public function __construct(
        private StockAdjustmentService $stockAdjustmentService,
        private StockAdjustmentProductService $stockAdjustmentProductService,
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
        private StockChainService $stockChainService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private UserActivityLogService $userActivityLogService,
    ) {
    }

    public function indexMethodContainer(object $request): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->stockAdjustmentService->stockAdjustmentsTable(request: $request);
        }

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return $data;
    }

    public function showMethodContainer(int $id): ?array
    {
        $data = [];
        $data['adjustment'] = $this->stockAdjustmentService->singleStockAdjustment(id: $id, with: [
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

    public function createMethodContainer(): array
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
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['expenseAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 10)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['warehouses'] = $this->warehouseService->warehouses()
            ->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)
            ->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): ?array {

        $restrictions = $this->stockAdjustmentService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $voucherPrefix = $generalSettings['prefix__stock_adjustment_prefix'] ? $generalSettings['prefix__stock_adjustment_prefix'] : 'SA';
        $receiptVoucherPrefix = $generalSettings['prefix__receipt_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'RV';
        $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        $addStockAdjustment = $this->stockAdjustmentService->addStockAdjustment(request: $request, codeGenerator: $codeGenerator, voucherPrefix: $voucherPrefix);

        // Add Day Book Entry
        $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::StockAdjustment->value, date: $request->date, accountId: $request->expense_account_id, transId: $addStockAdjustment->id, amount: $request->net_total_amount, amountType: 'debit');

        // Add Expense Ledger Entry
        $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::StockAdjustment->value, account_id: $request->expense_account_id, date: $request->date, trans_id: $addStockAdjustment->id, amount: $request->net_total_amount, amount_type: 'debit');

        foreach ($request->product_ids as $index => $product_id) {

            $addStockAdjustmentProduct = $this->stockAdjustmentProductService->addStockAdjustmentProduct(request: $request, stockAdjustmentId: $addStockAdjustment->id, index: $index);

            $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::StockAdjustment->value, date: $request->date, productId: $addStockAdjustmentProduct->product_id, transId: $addStockAdjustmentProduct->id, rate: $addStockAdjustmentProduct->unit_cost_inc_tax, quantityType: 'out', quantity: $addStockAdjustmentProduct->quantity, subtotal: $addStockAdjustmentProduct->subtotal, variantId: $addStockAdjustmentProduct->variant_id, warehouseId: $addStockAdjustmentProduct->warehouse_id);

            $this->productStockService->adjustMainProductAndVariantStock(productId: $addStockAdjustmentProduct->product_id, variantId: $addStockAdjustmentProduct->variant_id);

            $this->productStockService->adjustBranchAllStock(productId: $addStockAdjustmentProduct->product_id, variantId: $addStockAdjustmentProduct->variant_id, branchId: auth()->user()->branch_id);

            if ($addStockAdjustmentProduct->warehouse_id) {

                $this->productStockService->adjustWarehouseStock(productId: $addStockAdjustmentProduct->product_id, variantId: $addStockAdjustmentProduct->variant_id, warehouseId: $addStockAdjustmentProduct->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock(productId: $addStockAdjustmentProduct->product_id, variantId: $addStockAdjustmentProduct->variant_id, branchId: auth()->user()->branch_id);
            }

            $index++;
        }

        if ($request->recovered_amount > 0) {

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: null, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->recovered_amount, creditTotal: $request->recovered_amount, totalAmount: $request->recovered_amount, stockAdjustmentRefId: $addStockAdjustment->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->recovered_amount);

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->recovered_amount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->expense_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->recovered_amount, note: $request->payment_note);

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: null, amount: $request->recovered_amount, refIdColName: 'stock_adjustment_id', refIds: [$addStockAdjustment->id]);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->expense_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->recovered_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        $stockAdjustment = $this->stockAdjustmentService->singleStockAdjustment(id: $addStockAdjustment->id, with: ['adjustmentProducts']);

        $this->stockChainService->addStockChain(stockAdjustment: $stockAdjustment, stockAccountingMethod: $stockAccountingMethod);

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::StockAdjustment->value, dataObj: $addStockAdjustment);

        return null;
    }

    public function deleteMethodContainer(int $id): ?array {

        $deleteAdjustment = $this->stockAdjustmentService->deleteStockAdjustment(id: $id);

        if (isset($this->deleteAdjustment['pass']) && $this->deleteAdjustment['pass'] == false) {

            return ['pass' => false, 'msg' => $deleteAdjustment['msg']];
        }

        foreach ($deleteAdjustment->adjustmentProducts as $adjustmentProduct) {

            $this->productStockService->adjustMainProductAndVariantStock($adjustmentProduct->product_id, $adjustmentProduct->variant_id);

            if ($adjustmentProduct->warehouse_id) {

                $this->productStockService->adjustWarehouseStock($adjustmentProduct->product_id, $adjustmentProduct->variant_id, $adjustmentProduct->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock($adjustmentProduct->product_id, $adjustmentProduct->variant_id, $deleteAdjustment->branch_id);
            }

            foreach ($adjustmentProduct->stockChains as $stockChain) {

                if ($stockChain?->purchaseProduct) {

                    $this->stockChainService->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                }
            }
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::StockAdjustment->value, dataObj: $deleteAdjustment);

        return null;
    }

    public function printMethodContainer(int $id, object $request): ?array
    {
        $data = [];
        $data['adjustment'] = $this->stockAdjustmentService->singleStockAdjustment(id: $id, with: [
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
