<?php

namespace App\Services\Sales\MethodContainerServices;

use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Enums\SaleScreenType;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Services\Sales\SaleService;
use App\Enums\AccountingVoucherType;
use App\Services\Setups\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Sales\SalesOrderService;
use App\Services\Setups\WarehouseService;
use App\Services\Sales\SaleProductService;
use App\Services\Products\PriceGroupService;
use App\Services\Products\StockChainService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Products\ManagePriceGroupService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Interfaces\Sales\AddSaleControllerMethodContainersInterface;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class AddSaleControllerMethodContainersService implements AddSaleControllerMethodContainersInterface
{
    public function __construct(
        private SaleService $saleService,
        private SalesOrderService $salesOrderService,
        private SaleProductService $saleProductService,
        private StockChainService $stockChainService,
        private PriceGroupService $priceGroupService,
        private ManagePriceGroupService $managePriceGroupService,
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
        private UserActivityLogService $userActivityLogService,
    ) {
    }

    public function indexMethodContainer(int|string $customerAccountId = null, string $saleScreen = null, object $request): array|object
    {
        $data = [];
        if ($request->ajax()) {

            $customerAccountId = $customerAccountId == 'null' ? null : $customerAccountId;
            return $this->saleService->salesListTable(request: $request, customerAccountId: $customerAccountId, saleScreen: $saleScreen);
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
        $sale = $this->saleService->singleSale(id: $id, with: [
            'branch',
            'branch.parentBranch',
            'customer:id,name,phone,address',
            'createdBy:id,prefix,name,last_name',
            'salesOrder:id,order_id',
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

        $data['sale'] = $sale;

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

        $data['saleAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['warehouses'] = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['customerAccounts'] = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $data['priceGroupProducts'] = $this->managePriceGroupService->priceGroupProducts();

        $data['priceGroups'] = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): ?array
    {
        $generalSettings = config('generalSettings');
        $invoicePrefix = $generalSettings['prefix__sales_invoice_prefix'] ? $generalSettings['prefix__sales_invoice_prefix'] : 'SI';
        $quotationPrefix = $generalSettings['prefix__quotation_prefix'] ? $generalSettings['prefix__quotation_prefix'] : 'Q';
        $salesOrderPrefix = $generalSettings['prefix__sales_order_prefix'] ? $generalSettings['prefix__sales_order_prefix'] : 'SO';
        $receiptVoucherPrefix = $generalSettings['prefix__receipt_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'RV';
        $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        $restrictions = $this->saleService->restrictions(request: $request, accountService: $this->accountService);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $addSale = $this->saleService->addSale(request: $request, saleScreenType: SaleScreenType::AddSale->value, codeGenerator: $codeGenerator, invoicePrefix: $invoicePrefix, quotationPrefix: $quotationPrefix, salesOrderPrefix: $salesOrderPrefix);

        if ($request->status == SaleStatus::Final->value || $request->status == SaleStatus::Order->value) {

            $dayBookVoucherType = $request->status == SaleStatus::Final->value ? DayBookVoucherType::Sales->value : DayBookVoucherType::SalesOrder->value;

            // Add Day Book entry for Final Sale or Sales Order
            $this->dayBookService->addDayBook(voucherTypeId: $dayBookVoucherType, date: $request->date, accountId: $request->customer_account_id, transId: $addSale->id, amount: $request->total_invoice_amount, amountType: 'debit');
        }

        if ($request->status == SaleStatus::Final->value) {

            // Add Sale A/c Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, date: $request->date, account_id: $request->sale_account_id, trans_id: $addSale->id, amount: $request->sales_ledger_amount, amount_type: 'credit');

            // Add supplier A/c ledger Entry For Sales
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->customer_account_id, date: $request->date, trans_id: $addSale->id, amount: $request->total_invoice_amount, amount_type: 'debit');

            if ($request->sale_tax_ac_id) {

                // Add Tax A/c ledger Entry For Sales
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->sale_tax_ac_id, date: $request->date, trans_id: $addSale->id, amount: $request->order_tax_amount, amount_type: 'credit');
            }
        }

        $index = 0;
        foreach ($request->product_ids as $productId) {

            $addSaleProduct = $this->saleProductService->addSaleProduct(request: $request, sale: $addSale, index: $index);

            if ($request->status == SaleStatus::Final->value) {

                // Add Product Ledger Entry
                $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Sales->value, date: $request->date, productId: $productId, transId: $addSaleProduct->id, rate: $addSaleProduct->unit_price_inc_tax, quantityType: 'out', quantity: $addSaleProduct->quantity, subtotal: $addSaleProduct->subtotal, variantId: $addSaleProduct->variant_id, warehouseId: (isset($request->warehouse_ids[$index]) ? $request->warehouse_ids[$index] : null));

                if ($addSaleProduct->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SaleProductTax->value, date: $request->date, account_id: $addSaleProduct->tax_ac_id, trans_id: $addSaleProduct->id, amount: ($addSaleProduct->unit_tax_amount * $addSaleProduct->quantity), amount_type: 'credit');
                }
            }

            $index++;
        }

        if (($request->status == SaleStatus::Final->value || $request->status == SaleStatus::Order->value) && $request->received_amount > 0) {

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, saleRefId: $addSale->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount);

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount, note: $request->payment_note);

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: [$addSale->id]);

            // Add Day Book entry for Receipt
            $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Receipt->value, date: $request->date, accountId: $request->customer_account_id, transId: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amountType: 'credit');

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        $sale = $this->saleService->singleSale(
            id: $addSale->id,
            with: [
                'branch',
                'branch.parentBranch',
                'customer',
                'saleProducts',
                'saleProducts.product',
            ]
        );

        if ($sale->due > 0 && $sale->status == SaleStatus::Final->value) {

            $this->accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->customer_account_id, accountingVoucherType: AccountingVoucherType::Receipt->value, refIdColName: 'sale_id', sale: $sale);
        }

        if ($request->status == SaleStatus::Final->value) {

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

                $this->stockChainService->addStockChain(sale: $sale, stockAccountingMethod: $stockAccountingMethod);

                $__index++;
            }
        }

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

        $subjectType = '';
        if ($request->status == SaleStatus::Final->value) {

            $subjectType = UserActivityLogSubjectType::Sales->value;
        } elseif ($request->status == SaleStatus::Order->value) {

            $subjectType = UserActivityLogSubjectType::SalesOrder->value;
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $subjectType = UserActivityLogSubjectType::Quotation->value;
        } elseif ($request->status == SaleStatus::Draft->value) {

            $subjectType = UserActivityLogSubjectType::Draft->value;;
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: $subjectType, dataObj: $sale);

        return ['sale' => $sale, 'customerCopySaleProducts' => $customerCopySaleProducts];
    }

    public function printTemplateBySaleStatus(object $request, object $sale, object $customerCopySaleProducts): mixed
    {
        return $this->saleService->printTemplateBySaleStatus(request: $request, sale: $sale, customerCopySaleProducts: $customerCopySaleProducts);
    }

    public function editMethodContainer(int $id): array
    {
        $sale = $this->saleService->singleSale(id: $id, with: [
            'customer',
            'customer.group',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,name,code_name,base_unit_id',
        ]);

        $ownBranchIdOrParentBranchId = $sale?->branch?->parent_branch_id ? $sale?->branch?->parent_branch_id : $sale->branch_id;

        $data['branchName'] = $this->branchService->branchName(transObject: $sale);

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $sale->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['saleAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', $sale->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['warehouses'] = $this->warehouseService->warehouses()->where('branch_id', $sale->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $sale->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['customerAccounts'] = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $data['priceGroupProducts'] = $this->managePriceGroupService->priceGroupProducts();

        $data['priceGroups'] = $this->priceGroupService->priceGroups()->get(['id', 'name']);
        $data['sale'] = $sale;

        return $data;
    }

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array
    {
        $restrictions = $this->saleService->restrictions(request: $request, accountService: $this->accountService, checkCustomerChangeRestriction: true, saleId: $id);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $receiptVoucherPrefix = $generalSettings['prefix__receipt_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'RV';
        $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        $sale = $this->saleService->singleSale(id: $id, with: ['saleProducts']);

        $storedCurrSaleAccountId = $sale->sale_account_id;
        $storedCurrCustomerAccountId = $sale->customer_account_id;
        $storedCurrSaleTaxAccountId = $sale->sale_tax_ac_id;

        $updateSale = $this->saleService->updateSale(request: $request, updateSale: $sale);

        // Update Day Book entry for Sale
        $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Sales->value, date: $request->date, accountId: $request->customer_account_id, transId: $updateSale->id, amount: $request->total_invoice_amount, amountType: 'debit');

        // Update Sale A/c Ledger Entry
        $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, date: $request->date, account_id: $request->sale_account_id, trans_id: $updateSale->id, amount: $request->sales_ledger_amount, amount_type: 'credit', current_account_id: $storedCurrSaleAccountId, branch_id: $updateSale->branch_id);

        // Update customer A/c ledger Entry For sale
        $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->customer_account_id, date: $request->date, trans_id: $updateSale->id, amount: $request->total_invoice_amount, amount_type: 'debit', current_account_id: $storedCurrCustomerAccountId, branch_id: $updateSale->branch_id);

        if ($request->sale_tax_ac_id) {

            // Add Tax A/c ledger Entry For Sale
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->sale_tax_ac_id, date: $request->date, trans_id: $updateSale->id, amount: $request->order_tax_amount, amount_type: 'debit', current_account_id: $storedCurrSaleTaxAccountId, branch_id: $updateSale->branch_id);
        } else {

            $this->accountLedgerService->deleteUnusedLedgerEntry(voucherType: AccountLedgerVoucherType::Sales->value, transId: $updateSale->id, accountId: $storedCurrSaleTaxAccountId);
        }

        $index = 0;
        foreach ($request->product_ids as $productId) {

            $updateSaleProduct = $this->saleProductService->updateSaleProduct(request: $request, sale: $updateSale, index: $index);

            // Add Product Ledger Entry
            $quantity = $updateSaleProduct->quantity;
            $absQuantity = abs($updateSaleProduct->quantity);
            $voucherType = $updateSaleProduct->ex_status == BooleanType::True->value ? ProductLedgerVoucherType::Exchange->value : ProductLedgerVoucherType::Sales->value;
            $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Sales->value, date: $request->date, productId: $productId, transId: $updateSaleProduct->id, rate: $updateSaleProduct->unit_price_inc_tax, quantityType: ($quantity >= 0 ? 'out' : 'in'), quantity: $absQuantity, subtotal: $updateSaleProduct->subtotal, variantId: $updateSaleProduct->variant_id, warehouseId: (isset($request->warehouse_ids[$index]) ? $request->warehouse_ids[$index] : null), currentWarehouseId: $updateSaleProduct->current_warehouse_id, branchId: $updateSale->branch_id);

            if ($updateSaleProduct->tax_ac_id) {

                // Add Tax A/c ledger Entry
                $voucherType = $updateSaleProduct->ex_status == BooleanType::True->value ? AccountLedgerVoucherType::Exchange->value : AccountLedgerVoucherType::SaleProductTax->value;
                $ledgerTaxAmount = $updateSaleProduct->unit_tax_amount * $updateSaleProduct->quantity;
                $absLedgerTaxAmount = abs($ledgerTaxAmount);
                $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: $voucherType, date: $request->date, account_id: $updateSaleProduct->tax_ac_id, trans_id: $updateSaleProduct->id, amount: $absLedgerTaxAmount, amount_type: ($ledgerTaxAmount >= 0 ? 'credit' : 'debit'), current_account_id: $updateSaleProduct->current_tax_ac_id, branch_id: $updateSale->branch_id);
            } else {

                $this->accountLedgerService->deleteUnusedLedgerEntry(voucherType: AccountLedgerVoucherType::SaleProductTax->value, transId: $updateSaleProduct->id, accountId: $updateSaleProduct->current_tax_ac_id);
            }

            $index++;
        }

        if ($request->received_amount > 0) {

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, saleRefId: $updateSale->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount);

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount, note: $request->payment_note);

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: [$updateSale->id]);

            //Add Daybook For Receipt
            $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Receipt->value, date: $request->date, accountId: $request->customer_account_id, transId: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amountType: 'credit');

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        $deletedUnusedSaleProducts = $this->saleProductService->saleProducts(with: ['stockChains', 'stockChains.purchaseProduct'])->where('sale_id', $updateSale->id)->where('is_delete_in_update', 1)->get();

        if (count($deletedUnusedSaleProducts) > 0) {

            foreach ($deletedUnusedSaleProducts as $deletedUnusedSaleProduct) {

                $deletedUnusedSaleProduct->delete();

                // Adjust deleted product stock
                $this->productStockService->adjustMainProductAndVariantStock($deletedUnusedSaleProduct->product_id, $deletedUnusedSaleProduct->variant_id);

                $this->productStockService->adjustBranchAllStock(productId: $deletedUnusedSaleProduct->product_id, variantId: $deletedUnusedSaleProduct->variant_id, branchId: $updateSale->branch_id);

                if (isset($deletedUnusedSaleProduct->warehouse_id)) {

                    $this->productStockService->adjustWarehouseStock(productId: $deletedUnusedSaleProduct->product_id, variantId: $deletedUnusedSaleProduct->variant_id, warehouseId: $deletedUnusedSaleProduct->warehouse_id);
                } else {

                    $this->productStockService->adjustBranchStock(productId: $deletedUnusedSaleProduct->product_id, variantId: $deletedUnusedSaleProduct->variant_id, branchId: $updateSale->branch_id);
                }

                foreach ($deletedUnusedSaleProduct->stockChains as $stockChain) {

                    $this->stockChainService->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                }
            }
        }

        $sale = $this->saleService->singleSale(id: $updateSale->id, with: [
            'salesOrder',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.stockChains',
            'saleProducts.stockChains.purchaseProduct',
        ]);

        if (isset($sale->salesOrder)) {

            $this->salesOrderService->calculateDeliveryLeftQty($sale->salesOrder);
        }

        $adjustedSale = $this->saleService->adjustSaleInvoiceAmounts(sale: $sale);

        if ($sale->due > 0 && $sale->status == SaleStatus::Final->value) {

            $this->accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->customer_account_id, accountingVoucherType: AccountingVoucherType::Receipt->value, refIdColName: 'sale_id', sale: $adjustedSale);
        }

        $saleProducts = $sale->saleProducts;
        foreach ($saleProducts as $saleProduct) {

            $this->productStockService->adjustMainProductAndVariantStock($saleProduct->product_id, $saleProduct->variant_id);

            $this->productStockService->adjustBranchAllStock(productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, branchId: $updateSale->branch_id);

            if ($saleProduct->warehouse_id) {

                $this->productStockService->adjustWarehouseStock(productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, warehouseId: $saleProduct->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock(productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, branchId: $saleProduct->branch_id);
            }
        }

        $this->stockChainService->updateStockChain(sale: $sale, stockAccountingMethod: $stockAccountingMethod);

        // Add user Log
        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Sales->value, dataObj: $sale);

        return null;
    }

    public function deleteMethodContainer(int $id): array|object
    {
        $deleteSale = $this->saleService->deleteSale($id);

        if (isset($deleteSale['pass']) && $deleteSale['pass'] == false) {

            return ['pass' => false, 'msg' => $deleteSale['msg']];
        }

        if ($deleteSale->status == SaleStatus::Final->value) {

            foreach ($deleteSale->saleProducts as $saleProduct) {

                $this->productStockService->adjustMainProductAndVariantStock($saleProduct->product_id, $saleProduct->variant_id);

                $this->productStockService->adjustBranchAllStock(productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, branchId: $saleProduct->branch_id);

                if ($saleProduct->warehouse_id) {

                    $this->productStockService->adjustWarehouseStock($saleProduct->product_id, $saleProduct->variant_id, $saleProduct->warehouse_id);
                } else {

                    $this->productStockService->adjustBranchStock($saleProduct->product_id, $saleProduct->variant_id, $saleProduct->branch_id);
                }

                foreach ($saleProduct->stockChains as $stockChain) {

                    if ($stockChain->purchaseProduct) {

                        $this->stockChainService->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                    }
                }
            }

            if (isset($deleteSale->salesOrder)) {

                $this->salesOrderService->calculateDeliveryLeftQty($deleteSale->salesOrder);
            }
        }

        $subjectType = '';
        if ($deleteSale->status == SaleStatus::Final->value) {

            $subjectType = UserActivityLogSubjectType::Sales->value;
        } elseif ($deleteSale->status == SaleStatus::Order->value) {

            $subjectType =  UserActivityLogSubjectType::SalesOrder->value;
        } elseif ($deleteSale->status == SaleStatus::Quotation->value) {

            $subjectType = UserActivityLogSubjectType::Quotation->value;
        } elseif ($deleteSale->status == SaleStatus::Draft->value) {

            $subjectType = UserActivityLogSubjectType::Draft->value;
        } elseif ($deleteSale->status == SaleStatus::Hold->value) {

            $subjectType = UserActivityLogSubjectType::HoldInvoice->value;
        } elseif ($deleteSale->status == SaleStatus::Suspended->value) {

            $subjectType = UserActivityLogSubjectType::SuspendInvoice->value;
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: $subjectType, dataObj: $deleteSale);

        return $deleteSale;
    }

    public function searchByInvoiceIdMethodContainer(string $keyWord): array|object
    {
        $sales = DB::table('sales')
            ->where('sales.invoice_id', 'like', "%{$keyWord}%")
            ->where('sales.branch_id', auth()->user()->branch_id)
            ->where('sales.status', SaleStatus::Final->value)
            ->select('sales.id as sale_id', 'sales.invoice_id', 'sales.customer_account_id')->limit(35)->get();

        if (count($sales) > 0) {

            return view('search_results_view.sale_invoice_search_result_list', compact('sales'));
        } else {

            return ['noResult' => 'no result'];
        }
    }
}
