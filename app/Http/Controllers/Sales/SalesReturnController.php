<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
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
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\WarehouseService;
use App\Services\Sales\SalesReturnService;
use App\Services\Products\PriceGroupService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Sales\SalesReturnProductService;
use App\Services\Products\ManagePriceGroupService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class SalesReturnController extends Controller
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
        if (!auth()->user()->can('return_access')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->salesReturnService->salesReturnListTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.sales_return.index', compact('branches', 'customerAccounts'));
    }

    public function show($id)
    {
        $return = $this->salesReturnService->singleSalesReturn(id: $id, with: [
            'sale',
            'customer:id,name,phone,address',
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

        return view('sales.sales_return.ajax_view.show', compact('return'));
    }

    public function create()
    {
        if (!auth()->user()->can('return_access')) {

            abort(403, 'Access Forbidden.');
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $generalSettings = config('generalSettings');
        $branchName = $this->branchService->branchName;

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $saleAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        $priceGroupProducts = $this->managePriceGroupService->priceGroupProducts();

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.sales_return.create', compact('accounts', 'methods', 'saleAccounts', 'warehouses', 'priceGroups', 'priceGroupProducts', 'taxAccounts', 'customerAccounts', 'branchName'));
    }

    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'customer_account_id' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'sale_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'sale_account_id.required' => __("Sale A/c is required."),
            'account_id.required' => __("Credit field must not be empty."),
            'payment_method_id.required' => __("Payment method field is required."),
            'customer_account_id.required' => __("Customer is required."),
        ]);

        if (isset($request->warehouse_count)) {

            $this->validate($request, ['warehouse_id' => 'required']);
        }

        try {

            DB::beginTransaction();

            $restrictions = $this->salesReturnService->restrictions($request);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $salesReturnVoucherPrefix = isset($branchSetting) && $branchSetting?->sales_return_prefix ? $branchSetting?->sales_return_prefix : $generalSettings['prefix__sale_return'];
            $paymentVoucherPrefix = isset($branchSetting) && $branchSetting?->payment_voucher_prefix ? $branchSetting?->payment_voucher_prefix : $generalSettings['prefix__payment'];

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

            $index = 0;
            foreach ($request->product_ids as $productId) {

                $addSaleReturnProduct = $this->salesReturnProductService->addSalesReturnProduct(request: $request, saleReturnId: $addReturn->id, index: $index);

                // Add Product Ledger Entry
                $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::SalesReturn->value, date: $request->date, productId: $productId, transId: $addSaleReturnProduct->id, rate: $addSaleReturnProduct->unit_price_inc_tax, quantityType: 'in', quantity: $addSaleReturnProduct->return_qty, subtotal: $addSaleReturnProduct->return_subtotal, variantId: $addSaleReturnProduct->variant_id, warehouseId: (isset($addReturn->warehouse_count) ? $addReturn->warehouse_id : null));

                // Sales Return product tax will be go here
                if ($addSaleReturnProduct->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SalesReturnProductTax->value, date: $request->date, account_id: $addSaleReturnProduct->tax_ac_id, trans_id: $addSaleReturnProduct->id, amount: ($addSaleReturnProduct->unit_tax_amount * $addSaleReturnProduct->return_qty), amount_type: 'debit');
                }

                if ($addSaleReturnProduct->return_qty > 0) {

                    $this->purchaseProductService->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(transColName: 'sale_return_product_id', transId: $addSaleReturnProduct->id, branchId: auth()->user()->branch_id, productId: $addSaleReturnProduct->product_id, variantId: $addSaleReturnProduct->variant_id, quantity: $addSaleReturnProduct->return_qty, unitCostIncTax: $addSaleReturnProduct->unit_cost_inc_tax, sellingPrice: $addSaleReturnProduct->unit_price_inc_tax, subTotal: $addSaleReturnProduct->return_subtotal, createdAt: $addReturn->date_ts);
                }

                $index++;
            }

            if ($request->paying_amount > 0) {

                $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Payment->value, remarks: $request->receipt_note, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount, saleReturnRefId: $addReturn->id);

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

            $__index = 0;
            foreach ($request->product_ids as $productId) {

                $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

                if (isset($request->warehouse_count) && $request->warehouse_id) {

                    $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_id);
                } else {

                    $this->productStockService->adjustBranchStock($productId, $variantId, branchId: auth()->user()->branch_id);
                }

                $__index++;
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

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 9, data_obj: $addReturn);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            $paidAmount = $request->paying_amount;
            return view('sales.save_and_print_template.sales_return_print', compact('return', 'paidAmount'));
        } else {

            return response()->json(['successMsg' => __("Sales Return Created Successfully.")]);
        }
    }
}
