<?php

namespace App\Http\Controllers\Sales;

use App\Enums\SaleStatus;
use Illuminate\Http\Request;
use App\Enums\SaleScreenType;
use App\Enums\DayBookVoucherType;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Services\Sales\SaleService;
use App\Enums\AccountingVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Services\CodeGenerationService;
use App\Services\Sales\QuotationService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\WarehouseService;
use App\Services\Sales\SaleProductService;
use App\Services\Products\PriceGroupService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class AddSalesController extends Controller
{
    public function __construct(
        private SaleService $saleService,
        private SaleProductService $saleProductService,
        private PurchaseProductService $purchaseProductService,
        private PriceGroupService $priceGroupService,
        private PaymentMethodService $paymentMethodService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
        private BranchSettingService $branchSettingService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private AccountLedgerService $accountLedgerService,
        private ProductLedgerService $productLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_add_sale')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->saleService->addSalesListTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.add_sale.index', compact('branches', 'customerAccounts'));
    }

    public function show($id)
    {
        $sale = $this->saleService->singleSale(id: $id, with: [
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

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

        return view('sales.add_sale.ajax_views.show', compact('sale', 'customerCopySaleProducts'));
    }

    public function printChallan($id)
    {
        $sale = $this->saleService->singleSale(id: $id, with: [
            'customer:id,name,phone,address',
            'createdBy:id,prefix,name,last_name',
        ]);

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

        return view('sales.add_sale.ajax_views.print_challan', compact('sale', 'customerCopySaleProducts'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_add_sale')) {

            abort(403, 'Access Forbidden.');
        }

        $generalSettings = config('generalSettings');

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branchName = $generalSettings['business__shop_name'];
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch?->name . '(' . auth()->user()?->branch->parentBranch?->area_name . ')';
            } else {

                $branchName = auth()->user()?->branch?->name . '(' . auth()->user()?->branch?->area_name . ')';
            }
        }

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $saleAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return view('sales.add_sale.create', compact(
            'customerAccounts',
            'methods',
            'accounts',
            'saleAccounts',
            'taxAccounts',
            'priceGroups',
            'warehouses',
            'branchName'
        ));
    }

    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'status' => 'required',
            'date' => 'required|date',
            'sale_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'sale_account_id.required' => 'Sales A/c is required',
            'account_id.required' => 'Debit A/c is required',
        ]);

        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $invoicePrefix = isset($branchSetting) && $branchSetting?->sale_invoice_prefix ? $branchSetting?->sale_invoice_prefix : $generalSettings['prefix__sale_invoice'];
            $quotationPrefix = isset($branchSetting) && $branchSetting?->quotation_prefix ? $branchSetting?->quotation_prefix : 'Q';
            $salesOrderPrefix = isset($branchSetting) && $branchSetting?->sales_order_prefix ? $branchSetting?->sales_order_prefix : 'OR';
            $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt'];
            $stockAccountingMethod = $generalSettings['business__stock_accounting_method'];

            $restrictions = $this->saleService->restrictions(request: $request, accountService: $this->accountService);
            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
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

                // Add supplier A/c ledger Entry For Purchase
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->customer_account_id, date: $request->date, trans_id: $addSale->id, amount: $request->total_invoice_amount, amount_type: 'debit');


                if ($request->sale_tax_ac_id) {

                    // Add Tax A/c ledger Entry For Purchase
                    $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->sale_tax_ac_id, date: $request->date, trans_id: $addSale->id, amount: $request->order_tax_amount, amount_type: 'credit');
                }
            }

            $index = 0;
            foreach ($request->product_ids as $productId) {

                $addSaleProduct = $this->saleProductService->addSaleProduct(request: $request, sale: $addSale, index: $index);

                if ($request->status == SaleStatus::Final->value) {

                    // Add Product Ledger Entry
                    $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Sales->value, date: $request->date, productId: $productId, transId: $addSaleProduct->id, rate: $addSaleProduct->unit_price_inc_tax, quantityType: 'out', quantity: $addSaleProduct->quantity, subtotal: $addSaleProduct->subtotal, variantId: $addSaleProduct->variant_id, warehouseId: (isset($request->warehouse_count) ? $request->warehouse_id : null));

                    if ($addSaleProduct->tax_ac_id) {

                        // Add Tax A/c ledger Entry
                        $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType->SaleProductTax->value, date: $request->date, account_id: $addSaleProduct->tax_ac_id, trans_id: $addSaleProduct->id, amount: ($addSaleProduct->unit_tax_amount * $addSaleProduct->quantity), amount_type: 'credit');
                    }
                }

                $index++;
            }

            if (($request->status == SaleStatus::Final->value || $request->status == SaleStatus::Order->value) && $request->received_amount > 0) {

                $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Payment->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, saleRefId: $addSale->id);

                // Add Debit Account Accounting voucher Description
                $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount);

                //Add Debit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

                // Add Payment Description Credit Entry
                $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount, note: $request->payment_note);

                // Add Accounting VoucherDescription References
                $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: [$addSale->id]);

                //Add Credit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
            }

            $sale = $this->saleService->singleSale(
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

            if ($sale->due > 0) {

                $this->accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->customer_account_id, accountingVoucherType: AccountingVoucherType::Receipt->value, refIdColName: 'sale_id', sale: $sale);
            }

            if ($request->status == SaleStatus::Final->value) {

                $__index = 0;
                foreach ($request->product_ids as $productId) {

                    $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                    $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

                    if (isset($request->warehouse_ids[$__index])) {

                        $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_ids[$__index]);
                    } else {

                        $this->productStockService->adjustBranchStock($productId, $variantId, branchId: auth()->user()->branch_id);
                    }

                    $this->purchaseProductService->addPurchaseSaleProductChain($sale, $stockAccountingMethod);

                    $__index++;
                }
            }

            $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

            $this->userActivityLogUtil->addLog(action: 1, subject_type: $request->status == SaleStatus::Final->value ? 7 : 8, data_obj: $sale);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            if ($request->status == SaleStatus::Final->value) {

                $changeAmount = 0;
                $receivedAmount = $request->received_amount;
                return view('sales.save_and_print_template.sale_print', compact('sale', 'receivedAmount', 'changeAmount', 'customerCopySaleProducts'));
            } elseif ($request->status == SaleStatus::Draft->value) {

                $draft = $sale;
                return view('sales.save_and_print_template.draft_print', compact('draft', 'customerCopySaleProducts'));
            } elseif ($request->status == SaleStatus::Quotation->value) {

                $quotation = $sale;
                return view('sales.save_and_print_template.quotation_print', compact('quotation', 'customerCopySaleProducts'));
            } elseif ($request->status == SaleStatus::Order->value) {

                $order = $sale;
                $receivedAmount = $request->received_amount;
                return view('sales.save_and_print_template.order_print', compact('order', 'receivedAmount', 'customerCopySaleProducts'));
            }
        } else {

            return response()->json(['saleFinalMsg' => 'Sale created successfully']);
        }
    }
}
