<?php

namespace App\Services\Sales\MethodContainerServices;

use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Enums\SaleScreenType;
use App\Enums\DayBookVoucherType;
use App\Services\Sales\SaleService;
use App\Enums\AccountingVoucherType;
use App\Services\Products\UnitService;
use App\Services\Sales\PosSaleService;
use App\Services\Branches\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Services\Products\BrandService;
use App\Enums\UserActivityLogActionType;
use App\Services\Services\DeviceService;
use App\Services\Services\StatusService;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Contacts\ContactService;
use App\Services\Services\JobCardService;
use App\Services\Products\CategoryService;
use App\Services\Sales\SaleProductService;
use App\Services\Sales\CashRegisterService;
use App\Services\Products\PriceGroupService;
use App\Services\Products\StockChainService;
use App\Services\Contacts\RewardPointService;
use App\Services\Services\DeviceModelService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Products\ManagePriceGroupService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Sales\CashRegisterTransactionService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Interfaces\Sales\PosSaleControllerMethodContainersInterface;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class PosSaleControllerMethodContainersService implements PosSaleControllerMethodContainersInterface
{
    public function __construct(
        private SaleService $saleService,
        private PosSaleService $posSaleService,
        private SaleProductService $saleProductService,
        private CashRegisterService $cashRegisterService,
        private CashRegisterTransactionService $cashRegisterTransactionService,
        private BrandService $brandService,
        private CategoryService $categoryService,
        private StockChainService $stockChainService,
        private PriceGroupService $priceGroupService,
        private ManagePriceGroupService $managePriceGroupService,
        private PaymentMethodService $paymentMethodService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private AccountLedgerService $accountLedgerService,
        private ProductLedgerService $productLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private ContactService $contactService,
        private RewardPointService $rewardPointService,
        private UnitService $unitService,
        private JobCardService $jobCardService,
        private DeviceService $deviceService,
        private DeviceModelService $deviceModelService,
        private StatusService $statusService,
        private UserActivityLogService $userActivityLogService,
    ) {}

    public function createMethodContainer(object $codeGenerator, int|string $jobCardId = 'no_id', ?int $saleScreenType = null): mixed
    {
        $openedCashRegister = $this->cashRegisterService->singleCashRegister(with: ['user', 'branch', 'branch.parentBranch', 'cashCounter'])
            ->where('user_id', auth()->user()->id)
            ->where('branch_id', auth()->user()->branch_id)
            ->where('status', BooleanType::True->value)
            ->first();

        if ($openedCashRegister) {

            $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

            $branchName = $this->branchService->branchName();

            $categories = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);

            $brands = $this->brandService->brands()->get(['id', 'name']);

            $units = $this->unitService->units()->get(['id', 'name', 'code_name']);

            $priceGroupProducts = $this->managePriceGroupService->priceGroupProducts();

            $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

            $accounts = $this->accountService->accounts(with: [
                'bank:id,name',
                'group:id,sorting_number,sub_sub_group_number',
                'bankAccessBranch',
            ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
                ->where('branch_id', auth()->user()->branch_id)
                ->whereIn('account_groups.sub_sub_group_number', [2])
                ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id', 'account_groups.sub_sub_group_number')
                ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

            $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

            $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

            $taxAccounts = $this->accountService->accounts()
                ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
                ->where('account_groups.sub_sub_group_number', 8)
                ->get(['accounts.id', 'accounts.name', 'tax_percent']);

            $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

            $voucherNo = $this->saleService->salesInvoiceOrOthersId(codeGenerator: $codeGenerator);

            $jobCardData = [];

            if ($saleScreenType == SaleScreenType::ServicePosSale->value) {

                $generalSettings = config('generalSettings');

                if (isset($jobCardId) && $jobCardId != 'no_id') {

                    $jobCardId = filter_var($jobCardId, FILTER_VALIDATE_INT);

                    if (isset($jobCardId)) {

                        $jobCardData['jobCard'] = $this->jobCardService->singleJobCard(id: $jobCardId, with: [
                            'jobCardProducts',
                            'jobCardProducts.product',
                            'jobCardProducts.product.unit',
                            'jobCardProducts.variant',
                            'jobCardProducts.unit:id,name,code_name,base_unit_id,base_unit_multiplier',
                            'jobCardProducts.unit.baseUnit:id,base_unit_id,name,code_name',
                        ]);
                    }
                }

                $jobCardData['devices'] = $this->deviceService->devices()->where('branch_id', $ownBranchIdOrParentBranchId)->get(['id', 'name']);

                $jobCardData['deviceModels'] = $this->deviceModelService->deviceModels()->where('branch_id', $ownBranchIdOrParentBranchId)->get(['id', 'name', 'service_checklist']);

                $jobCardData['status'] = $this->statusService->allStatus()->where('service_status.branch_id', $ownBranchIdOrParentBranchId)->orderByRaw('ISNULL(sort_order), sort_order ASC')->get(['id', 'name', 'color_code']);

                $jobCardData['defaultProblemsReportItems'] = isset($generalSettings['service_settings__default_problems_report']) ? explode(',', $generalSettings['service_settings__default_problems_report']) : [];

                $jobCardData['defaultChecklist'] = isset($generalSettings['service_settings__default_checklist']) ? $generalSettings['service_settings__default_checklist'] : null;
            }

            $data = array_merge(compact(
                'branchName',
                'openedCashRegister',
                'categories',
                'brands',
                'units',
                'priceGroups',
                'priceGroupProducts',
                'accounts',
                'methods',
                'taxAccounts',
                'customerAccounts',
                'saleScreenType',
                'voucherNo',
            ), $jobCardData);

            return view('sales.pos.create', $data);
        } else {

            return redirect()->route('cash.register.create', [BooleanType::False->value, $jobCardId, $saleScreenType]);
        }
    }

    public function storeMethodContainer(object $request, object $codeGenerator): array|object
    {
        $generalSettings = config('generalSettings');
        $invoicePrefix = $generalSettings['prefix__sales_invoice_prefix'] ? $generalSettings['prefix__sales_invoice_prefix'] : 'SI';
        $quotationPrefix = $generalSettings['prefix__quotation_prefix'] ? $generalSettings['prefix__quotation_prefix'] : 'Q';
        $receiptVoucherPrefix = $generalSettings['prefix__receipt_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'RV';

        $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        $restrictions = $this->saleService->restrictions(request: $request, accountService: $this->accountService);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $saleScreenType = $request->sale_screen_type == SaleScreenType::ServicePosSale->value ? SaleScreenType::ServicePosSale->value : SaleScreenType::PosSale->value;

        $addPosSale = $this->posSaleService->addPosSale(request: $request, saleScreenType: $saleScreenType, codeGenerator: $codeGenerator, invoicePrefix: $invoicePrefix, quotationPrefix: $quotationPrefix, dateFormat: $generalSettings['business_or_shop__date_format']);

        if ($request->status == SaleStatus::Final->value) {

            // Add Day Book entry for Final Sale
            $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Sales->value, date: date('Y-m-d'), accountId: $request->customer_account_id, transId: $addPosSale->id, amount: $request->total_invoice_amount, amountType: 'debit');
        }

        if ($request->status == SaleStatus::Final->value) {

            // Add Sale A/c Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, date: date('Y-m-d'), account_id: $request->sale_account_id, trans_id: $addPosSale->id, amount: $request->sales_ledger_amount, amount_type: 'credit');

            // Add supplier A/c ledger Entry For Sale
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->customer_account_id, date: date('Y-m-d'), trans_id: $addPosSale->id, amount: $request->total_invoice_amount, amount_type: 'debit');

            if ($request->sale_tax_ac_id) {

                // Add Tax A/c ledger Entry For Sale
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->sale_tax_ac_id, date: date('Y-m-d'), trans_id: $addPosSale->id, amount: $request->order_tax_amount, amount_type: 'credit');
            }
        }

        foreach ($request->product_ids as $index => $productId) {

            $addSaleProduct = $this->saleProductService->addSaleProduct(request: $request, sale: $addPosSale, index: $index);

            if ($request->status == SaleStatus::Final->value) {

                // Add Product Ledger Entry
                $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Sales->value, date: date('Y-m-d'), productId: $productId, transId: $addSaleProduct->id, rate: $addSaleProduct->unit_price_inc_tax, quantityType: 'out', quantity: $addSaleProduct->quantity, subtotal: $addSaleProduct->subtotal, variantId: $addSaleProduct->variant_id);

                if ($addSaleProduct->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SaleProductTax->value, date: date('Y-m-d'), account_id: $addSaleProduct->tax_ac_id, trans_id: $addSaleProduct->id, amount: ($addSaleProduct->unit_tax_amount * $addSaleProduct->quantity), amount_type: 'credit');
                }
            }
        }

        $voucherDebitDescriptionId = null;
        if ($request->status == SaleStatus::Final->value && $request->received_amount > 0) {

            $changeAmount = $request->change_amount > 0 ? $request->change_amount : 0;
            $receivedAmount = $request->received_amount - $changeAmount;

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: date('Y-m-d'), voucherType: AccountingVoucherType::Receipt->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $receivedAmount, creditTotal: $receivedAmount, totalAmount: $receivedAmount, saleRefId: $addPosSale->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $receivedAmount);

            $voucherDebitDescriptionId = $addAccountingVoucherDebitDescription->id;

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: date('Y-m-d'), account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $receivedAmount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $receivedAmount, note: $request->payment_note);

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $receivedAmount, refIdColName: 'sale_id', refIds: [$addPosSale->id]);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: date('Y-m-d'), account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $receivedAmount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        if ($request->sale_screen_type == SaleScreenType::ServicePosSale->value) {

            $this->jobCardService->addOrUpdateJobCardBySale(request: $request, saleId: $addPosSale->id);
        }

        $sale = $this->saleService->singleSale(
            id: $addPosSale->id,
            with: [
                'branch',
                'branch.parentBranch',
                'customer',
                'jobCard',
                'jobCard.status',
                'jobCard.brand',
                'jobCard.device',
                'jobCard.deviceModel',
                'saleProducts',
                'saleProducts.product',
            ]
        );

        if ($sale->due > 0 && $sale->status == SaleStatus::Final->value) {

            $this->accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->customer_account_id, accountingVoucherType: AccountingVoucherType::Receipt->value, refIdColName: 'sale_id', sale: $sale);
        }

        if ($request->status == SaleStatus::Final->value) {

            foreach ($request->product_ids as $__index => $productId) {

                $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

                $this->productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);

                $this->productStockService->adjustBranchStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);
            }

            $this->stockChainService->addStockChain(sale: $sale, stockAccountingMethod: $stockAccountingMethod);
            $this->cashRegisterTransactionService->addCashRegisterTransaction(request: $request, saleId: $sale->id, voucherDebitDescriptionId: $voucherDebitDescriptionId, saleRefId: $sale->id);
        }

        $customerRewardPointOnInvoice = $this->rewardPointService->calculateCustomerPoint(generalSettings: $generalSettings, totalAmount: $request->total_invoice_amount);

        $this->contactService->updateRewardPoint(contactId: $sale?->customer?->contact_id, pointOnInvoice: $customerRewardPointOnInvoice, currentRedeemedPoint: (isset($request->pre_redeemed) ? (int) $request->pre_redeemed : 0));

        $this->saleService->updateInvoiceRewardPoint(sale: $sale, earnedPoint: $customerRewardPointOnInvoice, redeemedPoint: (isset($request->pre_redeemed) ? (int) $request->pre_redeemed : 0));

        $subjectType = '';
        if ($request->status == SaleStatus::Final->value) {

            $subjectType = UserActivityLogSubjectType::Sales->value;
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $subjectType = UserActivityLogSubjectType::Quotation->value;
        } elseif ($request->status == SaleStatus::Draft->value) {

            $subjectType = UserActivityLogSubjectType::Draft->value;
        } elseif ($request->status == SaleStatus::Hold->value) {

            $subjectType = UserActivityLogSubjectType::HoldInvoice->value;
        } elseif ($request->status == SaleStatus::Suspended->value) {

            $subjectType = UserActivityLogSubjectType::SuspendInvoice->value;
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: $subjectType, dataObj: $sale);

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

        return ['sale' => $sale, 'customerCopySaleProducts' => $customerCopySaleProducts];
    }

    public function editMethodContainer(int $id, ?int $saleScreenType = null): mixed
    {
        $openedCashRegister = $this->cashRegisterService->singleCashRegister(with: ['user', 'branch', 'branch.parentBranch', 'cashCounter'])
            ->where('user_id', auth()->user()->id)
            ->where('status', BooleanType::True->value)
            ->first();

        if ($openedCashRegister) {

            $sale = $this->saleService->singleSale(id: $id, with: [
                'branch',
                'branch.parentBranch',

                'jobCard',
                'jobCard.status',
                'jobCard.brand',
                'jobCard.device',
                'jobCard.deviceModel',

                'saleProducts',
                'saleProducts.product',
                'saleProducts.variant',
                'saleProducts.warehouse:id,warehouse_name,warehouse_code',
                'saleProducts.unit:id,name,code_name,base_unit_id,base_unit_multiplier',
                'saleProducts.unit.baseUnit:id,name,code_name,base_unit_id',
            ]);

            abort_if(!$sale, 404);

            $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

            $branchName = $this->branchService->branchName(transObject: $sale);

            $categories = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);

            $brands = $this->brandService->brands()->get(['id', 'name']);

            $units = $this->unitService->units()->get(['id', 'name', 'code_name']);

            $priceGroupProducts = $this->managePriceGroupService->priceGroupProducts();

            $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

            $accounts = $this->accountService->accounts(with: [
                'bank:id,name',
                'group:id,sorting_number,sub_sub_group_number',
                'bankAccessBranch',
            ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
                ->where('branch_id', auth()->user()->branch_id)
                ->whereIn('account_groups.sub_sub_group_number', [2])
                ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id', 'account_groups.sub_sub_group_number')
                ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

            $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

            $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

            $taxAccounts = $this->accountService->accounts()
                ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
                ->where('account_groups.sub_sub_group_number', 8)
                ->get(['accounts.id', 'accounts.name', 'tax_percent']);

            $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

            $jobCardData = [];
            if ($saleScreenType == SaleScreenType::ServicePosSale->value) {

                $generalSettings = config('generalSettings');

                $jobCardData['devices'] = $this->deviceService->devices()->where('branch_id', $ownBranchIdOrParentBranchId)->get(['id', 'name']);

                $jobCardData['deviceModels'] = $this->deviceModelService->deviceModels()->where('branch_id', $ownBranchIdOrParentBranchId)->get(['id', 'name', 'service_checklist']);

                $jobCardData['status'] = $this->statusService->allStatus()->where('service_status.branch_id', $ownBranchIdOrParentBranchId)->orderByRaw('ISNULL(sort_order), sort_order ASC')->get(['id', 'name', 'color_code']);

                $jobCardData['defaultProblemsReportItems'] = isset($generalSettings['service_settings__default_problems_report']) ? explode(',', $generalSettings['service_settings__default_problems_report']) : [];
            }

            $data = array_merge(compact(
                'sale',
                'branchName',
                'openedCashRegister',
                'categories',
                'brands',
                'units',
                'priceGroups',
                'priceGroupProducts',
                'accounts',
                'methods',
                'taxAccounts',
                'customerAccounts',
                'saleScreenType',
            ), $jobCardData);

            return view('sales.pos.edit', $data);
        } else {

            return redirect()->route('cash.register.create', [$id, 'no_id', $saleScreenType]);
        }
    }

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): array|object
    {
        $generalSettings = config('generalSettings');
        $invoicePrefix = $generalSettings['prefix__sales_invoice_prefix'] ? $generalSettings['prefix__sales_invoice_prefix'] : 'SI';
        $quotationPrefix = $generalSettings['prefix__quotation_prefix'] ? $generalSettings['prefix__quotation_prefix'] : 'Q';
        $receiptVoucherPrefix = $generalSettings['prefix__receipt_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'RV';
        $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        $restrictions = $this->saleService->restrictions(request: $request, accountService: $this->accountService, checkCustomerChangeRestriction: true, saleId: $id);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $sale = $this->saleService->singleSale(id: $id, with: ['saleProducts']);
        $storedCurrSalesAccountId = $sale->sale_account_id;
        $storedCurrCustomerAccountId = $sale->customer_account_id;
        $storedCurrSaleTaxAccountId = $sale->sale_tax_ac_id;
        $storedCurrTotalInvoiceAmount = $sale->total_invoice_amount;

        $updatePosSale = $this->posSaleService->updatePosSale(updateSale: $sale, request: $request, codeGenerator: $codeGenerator, invoicePrefix: $invoicePrefix, quotationPrefix: $quotationPrefix, dateFormat: $generalSettings['business_or_shop__date_format']);

        if ($request->status == SaleStatus::Final->value) {

            // Update Day Book entry for Final Sale
            $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Sales->value, date: $updatePosSale->date, accountId: $updatePosSale->customer_account_id, transId: $updatePosSale->id, amount: $updatePosSale->total_invoice_amount, amountType: 'debit');
        }

        if ($request->status == SaleStatus::Final->value) {

            // Update Sale A/c Ledger Entry
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, date: $updatePosSale->date, account_id: $updatePosSale->sale_account_id, trans_id: $updatePosSale->id, amount: $request->sales_ledger_amount, amount_type: 'credit', current_account_id: $storedCurrSalesAccountId);

            // Update Customer A/c ledger Entry For Sale
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->customer_account_id, date: $updatePosSale->date, trans_id: $updatePosSale->id, amount: $updatePosSale->total_invoice_amount, amount_type: 'debit', current_account_id: $storedCurrCustomerAccountId);

            if ($updatePosSale->sale_tax_ac_id) {

                // Update Tax A/c ledger Entry For Pos Sale
                $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $updatePosSale->sale_tax_ac_id, date: $updatePosSale->date, trans_id: $updatePosSale->id, amount: $updatePosSale->order_tax_amount, amount_type: 'credit', current_account_id: $storedCurrSaleTaxAccountId);
            } else {

                $this->accountLedgerService->deleteUnusedLedgerEntry(voucherType: AccountLedgerVoucherType::Sales->value, transId: $updatePosSale->id, accountId: $storedCurrSaleTaxAccountId);
            }
        }

        foreach ($request->product_ids as $index => $productId) {

            $updateSaleProduct = $this->saleProductService->updateSaleProduct(request: $request, sale: $updatePosSale, index: $index);

            if ($request->status == SaleStatus::Final->value) {

                // Update Product Ledger Entry
                $quantity = $updateSaleProduct->quantity;
                $absQuantity = abs($updateSaleProduct->quantity);
                $voucherType = $updateSaleProduct->ex_status == BooleanType::True->value ? ProductLedgerVoucherType::Exchange->value : ProductLedgerVoucherType::Sales->value;
                $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Sales->value, date: $updatePosSale->date, productId: $productId, transId: $updateSaleProduct->id, rate: $updateSaleProduct->unit_price_inc_tax, quantityType: ($quantity >= 0 ? 'out' : 'in'), quantity: $absQuantity, subtotal: $updateSaleProduct->subtotal, variantId: $updateSaleProduct->variant_id, branchId: $updatePosSale->branch_id);

                if ($updateSaleProduct->tax_ac_id) {

                    // Update Tax A/c ledger Entry
                    $voucherType = $updateSaleProduct->ex_status == BooleanType::True->value ? AccountLedgerVoucherType::Exchange->value : AccountLedgerVoucherType::SaleProductTax->value;
                    $ledgerTaxAmount = $updateSaleProduct->unit_tax_amount * $updateSaleProduct->quantity;
                    $absLedgerTaxAmount = abs($ledgerTaxAmount);

                    $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: $voucherType, date: $request->date, account_id: $updateSaleProduct->tax_ac_id, trans_id: $updateSaleProduct->id, amount: $absLedgerTaxAmount, amount_type: ($ledgerTaxAmount >= 0 ? 'credit' : 'debit'), current_account_id: $updateSaleProduct->current_tax_ac_id, branch_id: $updatePosSale->branch_id);
                } else {

                    $this->accountLedgerService->deleteUnusedLedgerEntry(voucherType: AccountLedgerVoucherType::SaleProductTax->value, transId: $updateSaleProduct->id, accountId: $updateSaleProduct->current_tax_ac_id);
                }
            }
        }

        $voucherDebitDescriptionId = null;
        if ($request->status == SaleStatus::Final->value && $request->received_amount > 0) {

            $changeAmount = $request->change_amount > 0 ? $request->change_amount : 0;
            $receivedAmount = $request->received_amount - $changeAmount;

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: date('Y-m-d'), voucherType: AccountingVoucherType::Receipt->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $receivedAmount, creditTotal: $receivedAmount, totalAmount: $receivedAmount, saleRefId: $updatePosSale->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $receivedAmount);

            $voucherDebitDescriptionId = $addAccountingVoucherDebitDescription->id;

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: date('Y-m-d'), account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $receivedAmount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $receivedAmount, note: $request->payment_note);

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $receivedAmount, refIdColName: 'sale_id', refIds: [$updatePosSale->id]);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: date('Y-m-d'), account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $receivedAmount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        if ($request->status == SaleStatus::Final->value) {

            foreach ($request->product_ids as $__index => $productId) {

                $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

                $this->productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: $updatePosSale->branch_id);

                $this->productStockService->adjustBranchStock(productId: $productId, variantId: $variantId, branchId: $updatePosSale->branch_id);
            }

            $this->cashRegisterTransactionService->addCashRegisterTransaction(request: $request, saleId: $updatePosSale->id, voucherDebitDescriptionId: $voucherDebitDescriptionId, saleRefId: $updatePosSale->id);
        }

        $deletedUnusedSaleProducts = $this->saleProductService->saleProducts(with: ['stockChains', 'stockChains.purchaseProduct'])->where('sale_id', $updatePosSale->id)->where('is_delete_in_update', BooleanType::True->value)->get();

        if (count($deletedUnusedSaleProducts) > 0) {

            foreach ($deletedUnusedSaleProducts as $deletedUnusedSaleProduct) {

                $deletedUnusedSaleProduct->delete();

                // Adjust deleted product stock
                $this->productStockService->adjustMainProductAndVariantStock($deletedUnusedSaleProduct->product_id, $deletedUnusedSaleProduct->variant_id);

                $this->productStockService->adjustBranchAllStock(productId: $deletedUnusedSaleProduct->product_id, variantId: $deletedUnusedSaleProduct->variant_id, branchId: $updatePosSale->branch_id);

                $this->productStockService->adjustBranchStock(productId: $deletedUnusedSaleProduct->product_id, variantId: $deletedUnusedSaleProduct->variant_id, branchId: $updatePosSale->branch_id);

                foreach ($deletedUnusedSaleProduct->stockChains as $stockChain) {

                    $this->stockChainService->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                }
            }
        }

        if ($request->sale_screen_type == SaleScreenType::ServicePosSale->value) {

            $this->jobCardService->addOrUpdateJobCardBySale(request: $request, saleId: $updatePosSale->id);
        }

        $sale = $this->saleService->singleSale(
            id: $updatePosSale->id,
            with: [
                'branch',
                'branch.parentBranch',
                'customer',
                'saleProducts',
                'saleProducts.product',
            ]
        );

        if ($sale->status == SaleStatus::Final->value) {

            $this->stockChainService->updateStockChain(sale: $sale, stockAccountingMethod: $stockAccountingMethod);
        }

        $adjustedSale = $this->saleService->adjustSaleInvoiceAmounts(sale: $sale);

        if ($sale->due > 0 && $sale->status == SaleStatus::Final->value) {

            $this->accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->customer_account_id, accountingVoucherType: AccountingVoucherType::Receipt->value, refIdColName: 'sale_id', sale: $adjustedSale);
        }

        $pointCalculableTotalAmount = (int) ($storedCurrTotalInvoiceAmount - $request->total_invoice_amount);

        if ($pointCalculableTotalAmount != 0) {

            $customerRewardPointOnInvoice = $this->rewardPointService->calculateCustomerPoint(generalSettings: $generalSettings, totalAmount: $pointCalculableTotalAmount);

            $this->contactService->updateRewardPoint(contactId: $sale?->customer?->contact_id, pointOnInvoice: $customerRewardPointOnInvoice);

            $this->saleService->updateInvoiceRewardPoint(sale: $sale, earnedPoint: $customerRewardPointOnInvoice);
        }

        $subjectType = '';
        if ($request->status == SaleStatus::Final->value) {

            $subjectType = UserActivityLogSubjectType::Sales->value;
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $subjectType = UserActivityLogSubjectType::Quotation->value;
        } elseif ($request->status == SaleStatus::Draft->value) {

            $subjectType = UserActivityLogSubjectType::Draft->value;
        } elseif ($request->status == SaleStatus::Hold->value) {

            $subjectType = UserActivityLogSubjectType::HoldInvoice->value;
        } elseif ($request->status == SaleStatus::Suspended->value) {

            $subjectType = UserActivityLogSubjectType::SuspendInvoice->value;
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: $subjectType, dataObj: $sale);

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

        return ['sale' => $sale, 'customerCopySaleProducts' => $customerCopySaleProducts];
    }

    public function printTemplateBySaleStatusForStore(object $request, object $sale, object $customerCopySaleProducts): mixed
    {
        return $this->posSaleService->printTemplateBySaleStatusForStore(request: $request, sale: $sale, customerCopySaleProducts: $customerCopySaleProducts);
    }

    public function printTemplateBySaleStatusForUpdate(object $request, object $sale, object $customerCopySaleProducts): mixed
    {
        return $this->posSaleService->printTemplateBySaleStatusForUpdate(request: $request, sale: $sale, customerCopySaleProducts: $customerCopySaleProducts);
    }
}
