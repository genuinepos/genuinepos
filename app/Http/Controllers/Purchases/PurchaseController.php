<?php

namespace App\Http\Controllers\Purchases;

use Illuminate\Http\Request;
use App\Enums\PurchaseStatus;
use App\Mail\PurchaseCreated;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Services\Setups\BranchService;
use App\Services\CodeGenerationService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Products\ProductService;
use App\Services\Setups\WarehouseService;
use App\Services\Purchases\PurchaseService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountGroupService;
use App\Services\Products\ProductStockService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\GeneralSettingServiceInterface;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Accounts\AccountingVoucherService;
use Modules\Communication\Interface\EmailServiceInterface;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class PurchaseController extends Controller
{
    public function __construct(
        private PurchaseService $purchaseService,
        private PurchaseProductService $purchaseProductService,
        private EmailServiceInterface $emailService,
        private UserActivityLogUtil $userActivityLogUtil,
        private PaymentMethodService $paymentMethodService,
        private AccountService $accountService,
        private AccountGroupService $accountGroupService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
        private BranchSettingService $branchSettingService,
        private ProductService $productService,
        private ProductStockService $productStockService,
        private UnitService $unitService,
        private DayBookService $dayBookService,
        private AccountLedgerService $accountLedgerService,
        private ProductLedgerService $productLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('purchase_all')) {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseService->purchaseListTable($request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $purchaseAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.purchases.index', compact('branches', 'suppliers', 'purchaseAccounts'));
    }

    public function purchaseProductList(Request $request)
    {
        if (!auth()->user()->can('purchase_all')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseUtil->purchaseProductListTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $suppliers = DB::table('suppliers')->get(['id', 'name', 'phone']);
        $categories = DB::table('categories')->where('parent_category_id', null)->get(['id', 'name']);

        return view('purchases.purchase_product_list', compact('branches', 'suppliers', 'categories'));
    }

    // show purchase details
    public function show($purchaseId)
    {
        $purchase = Purchase::with([
            'warehouse',
            'branch',
            'supplier',
            'admin',
            'purchase_products',
            'purchase_products.product',
            'purchase_products.product.warranty',
            'purchase_products.variant',
            'purchase_payments',
        ])->where('id', $purchaseId)->first();

        return view('purchases.ajax_view.purchase_details_modal', compact('purchase'));
    }

    public function create()
    {
        if (!auth()->user()->can('purchase_add')) {

            abort(403, 'Access Forbidden.');
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

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

        $purchaseAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.purchases.create', compact('warehouses', 'methods', 'accounts', 'purchaseAccounts', 'taxAccounts', 'supplierAccounts'));
    }

    // add purchase method
    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'supplier_account_id' => 'required',
            'invoice_id' => 'sometimes|unique:purchases,invoice_id',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'purchase_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'purchase_account_id.required' => __("Purchase A/c is required."),
            'account_id.required' => __("Credit field must not be is empty."),
            'payment_method_id.required' => __("Payment method field is required."),
            'supplier_account_id.required' => __("Supplier is required."),
        ]);

        if (isset($request->warehouse_count)) {

            $this->validate($request, ['warehouse_id' => 'required']);
        }

        $restrictions = $this->purchaseService->restrictions($request);

        if ($restrictions['pass'] == false) {

            return response()->json(['errorMsg' => $restrictions['msg']]);
        }

        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $invoicePrefix = isset($branchSetting) && $branchSetting?->purchase_invoice_prefix ? $branchSetting?->purchase_invoice_prefix : $generalSettings['prefix__purchase_invoice'];
            $paymentVoucherPrefix = isset($branchSetting) && $branchSetting?->payment_voucher_prefix ? $branchSetting?->payment_voucher_prefix : $generalSettings['prefix__payment'];
            $isEditProductPrice = $generalSettings['purchase__is_edit_pro_price'];

            $updateLastCreated = $this->purchaseService->purchaseByAnyConditions()->where('is_last_created', 1)->where('branch_id', auth()->user()->branch_id)->select('id', 'is_last_created')->first();

            if ($updateLastCreated) {

                $updateLastCreated->is_last_created = 0;
                $updateLastCreated->save();
            }

            $addPurchase = $this->purchaseService->addPurchase(request: $request, codeGenerator: $codeGenerator, invoicePrefix: $invoicePrefix);

            // Add Day Book entry for Purchase
            $this->dayBookService->addDayBook(voucherTypeId: 4, date: $request->date, accountId: $request->supplier_account_id, transId: $addPurchase->id, amount: $request->total_purchase_amount, amountType: 'credit');

            // Add Purchase A/c Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 3, date: $request->date, account_id: $request->purchase_account_id, trans_id: $addPurchase->id, amount: $request->purchase_ledger_amount, amount_type: 'debit');

            // Add supplier A/c ledger Entry For Purchase
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 3, account_id: $request->supplier_account_id, date: $request->date, trans_id: $addPurchase->id, amount: $request->total_purchase_amount, amount_type: 'credit');

            if ($request->purchase_tax_ac_id) {

                // Add Tax A/c ledger Entry For Purchase
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 3, account_id: $request->purchase_tax_ac_id, date: $request->date, trans_id: $addPurchase->id, amount: $request->purchase_tax_amount, amount_type: 'debit');
            }

            $index = 0;
            foreach ($request->product_ids as $productId) {

                $addPurchaseProduct = $this->purchaseProductService->addPurchaseProduct(request: $request, purchaseId: $addPurchase->id, isEditProductPrice: $isEditProductPrice, index: $index);

                // Add Product Ledger Entry
                $this->productLedgerService->addProductLedgerEntry(voucherTypeId: 3, date: $request->date, productId: $productId, transId: $addPurchaseProduct->id, rate: $addPurchaseProduct->net_unit_cost, quantityType: 'in', quantity: $addPurchaseProduct->quantity, subtotal: $addPurchaseProduct->line_total, variantId: $addPurchaseProduct->variant_id, warehouseId: (isset($request->warehouse_count) ? $request->warehouse_id : null));

                // purchase product tax will be go here
                if ($addPurchaseProduct->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 17, date: $request->date, account_id: $addPurchaseProduct->tax_ac_id, trans_id: $addPurchaseProduct->id, amount: ($addPurchaseProduct->unit_tax_amount * $addPurchaseProduct->quantity), amount_type: 'debit');
                }

                $index++;
            }

            if ($request->paying_amount > 0) {

                $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Payment->value, remarks: null, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount, purchaseRefId: $addPurchase->id);

                // Add Debit Account Accounting voucher Description
                $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->supplier_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

                // Add Accounting VoucherDescription References
                $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherDebitDescription->id, accountId: $request->supplier_account_id, amount: $request->paying_amount, refIdColName: 'purchase_id', refIds: [$addPurchase->id]);

                //Add Debit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 9, date: $request->date, account_id: $request->supplier_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->account_id);

                // Add Payment Description Credit Entry
                $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: null, amountType: 'cr', amount: $request->paying_amount, note: $request->payment_note);

                //Add Credit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 9, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'credit');
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
                ]
            )->where('id', $addPurchase->id)->first();

            if ($purchase->due > 0) {

                $this->accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->supplier_account_id, accountingVoucherType: 9, refIdColName: 'purchase_id', purchase: $purchase);
            }

            // update main product and variant price
            $loop = 0;
            foreach ($request->product_ids as $productId) {

                $variantId = $request->variant_ids[$loop] != 'noid' ? $request->variant_ids[$loop] : null;
                $__xMargin = isset($request->profits) ? $request->profits[$loop] : 0;
                $__selling_price = isset($request->selling_prices) ? $request->selling_prices[$loop] : 0;

                $this->productService->updateProductAndVariantPrice(productId: $productId, variantId: $variantId, unitCostWithDiscount: $request->unit_costs_with_discount[$loop], unitCostIncTax: $request->net_unit_costs[$loop], profit: $__xMargin, sellingPrice: $__selling_price, isEditProductPrice: $isEditProductPrice, isLastEntry: $purchase->is_last_created);

                $loop++;
            }

            $__index = 0;
            foreach ($request->product_ids as $productId) {

                $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

                if (isset($request->warehouse_count)) {

                    $this->productStockService->addWarehouseProduct(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_id);
                    $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_id);
                } else {

                    $this->productStockService->addBranchProduct(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);
                    $this->productStockService->adjustBranchStock($productId, $variantId, branchId: auth()->user()->branch_id);
                }

                $__index++;
            }

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 1, subject_type: PurchaseStatus::PurchaseOrder->value == 2 ? 5 : 4, data_obj: $purchase);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if (env('EMAIL_ACTIVE') == 'true' && $purchase?->supplier && $purchase?->supplier?->email) {

            $this->emailService->send($purchase->supplier->email, new PurchaseCreated($purchase));

            // $checkboxData = $request->input('checkboxes', []);
            // $resultArray = [];
            // foreach ($checkboxData as $model => $ids) {
            //     if ($model === 'users') {
            //         $users = User::whereIn('id', $ids)->select('email')->get();
            //         $resultArray['users'] = $users->toArray();
            //     } elseif ($model === 'customers') {
            //         $customers = Customer::whereIn('id', $ids)->select('email')->get();
            //         $resultArray['customers'] = $customers->toArray();
            //     }
            // }
            // $this->emailService->sendMultiple(array_values($resultArray, 'email'), new PurchaseCreated( $purchase));
        }

        if ($request->action == 2) {

            return response()->json(['successMsg' => __("Successfully purchase is created.")]);
        } else {

            $payingAmount = $request->paying_amount;
            return view('purchase.save_and_print_template.print_purchase', compact('purchase', 'payingAmount'));
        }
    }

    // Purchase edit view
    public function edit($purchaseId)
    {
        $warehouses = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', auth()->user()->branch_id)
            ->orWhere('warehouse_branches.is_global', 1)
            ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $purchase = DB::table('purchases')->where('id', $purchaseId)->select('id', 'warehouse_id', 'date', 'delivery_date', 'purchase_status')->first();

        $purchaseAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->where('accounts.account_type', 3)
            ->get(['accounts.id', 'accounts.name']);

        $purchase = Purchase::with(['warehouse', 'supplier', 'purchase_products', 'purchase_products.product', 'purchase_products.variant'])->where('id', $purchaseId)->first();

        $taxes = DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();
        $units = DB::table('units')->select('id', 'name')->get();

        return view('purchases.edit', compact('warehouses', 'purchase', 'purchaseAccounts', 'purchase', 'taxes', 'units'));
    }

    // update purchase method
    public function update(Request $request, $purchaseId)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'purchase_account_id' => 'required',
        ], [
            'purchase_account_id.required' => 'Purchase A/c is required.',
        ]);

        if (isset($request->warehouse_count)) {

            $this->validate($request, ['warehouse_id' => 'required']);
        }

        if (!isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Product table is empty.']);
        }

        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $paymentInvoicePrefix = $generalSettings['prefix__purchase_payment'];
            $isEditProductPrice = $generalSettings['purchase__is_edit_pro_price'];

            // get updatable purchase row
            $purchase = purchase::with(['purchase_products', 'purchase_order_products', 'ledger'])
                ->where('id', $purchaseId)->first();

            $storedWarehouseId = $purchase->warehouse_id;
            $storePurchaseProducts = $purchase->purchase_products;

            // update product and variant quantity for adjustment
            foreach ($purchase->purchase_products as $purchaseProduct) {

                $SupplierProduct = SupplierProduct::where('supplier_id', $purchase->supplier_id)
                    ->where('product_id', $purchaseProduct->product_id)
                    ->where('product_variant_id', $purchaseProduct->product_variant_id)
                    ->first();

                if ($SupplierProduct) {

                    $SupplierProduct->label_qty -= (float) $purchaseProduct->quantity;
                    $SupplierProduct->save();
                }
            }

            foreach ($purchase->purchase_products as $purchaseProduct) {

                $purchaseProduct->delete_in_update = 1;
                $purchaseProduct->save();
            }

            // update supplier product
            $i = 0;
            foreach ($request->product_ids as $productId) {

                $variantId = $request->variant_ids[$i] != 'noid' ? $request->variant_ids[$i] : null;

                $SupplierProduct = SupplierProduct::where('supplier_id', $purchase->supplier_id)
                    ->where('product_id', $productId)
                    ->where('product_variant_id', $variantId)
                    ->first();

                if (!$SupplierProduct) {

                    $addSupplierProduct = new SupplierProduct();
                    $addSupplierProduct->supplier_id = $purchase->supplier_id;
                    $addSupplierProduct->product_id = $productId;
                    $addSupplierProduct->product_variant_id = $variantId;
                    $addSupplierProduct->label_qty = $request->quantities[$i];
                    $addSupplierProduct->save();
                } else {

                    $SupplierProduct->label_qty = $SupplierProduct->label_qty + $request->quantities[$i];
                    $SupplierProduct->save();
                }
                $i++;
            }

            $updatePurchase = $this->purchaseUtil->updatePurchase($request, $purchase);

            // update product and variant Price & quantity
            $loop = 0;
            foreach ($request->product_ids as $productId) {

                $variantId = $request->variant_ids[$loop] != 'noid' ? $request->variant_ids[$loop] : null;

                $__xMargin = isset($request->profits) ? $request->profits[$loop] : 0;
                $__sellingPrice = isset($request->selling_prices) ? $request->selling_prices[$loop] : 0;

                $this->productUtil->updateProductAndVariantPrice(
                    productId: $productId,
                    variantId: $variantId,
                    unitCostWithDiscount: $request->unit_costs_with_discount[$loop],
                    unitCostIncTax: $request->net_unit_costs[$loop],
                    profit: $__xMargin,
                    sellingPrice: $__sellingPrice,
                    isEditProductPrice: $isEditProductPrice,
                    isLastEntry: $updatePurchase->is_last_created
                );

                $loop++;
            }

            $index = 0;
            foreach ($request->product_ids as $productId) {

                $updatePurchaseProduct = $this->purchaseProductUtil->updatePurchaseProduct(request: $request, purchaseId: $updatePurchase->id, isEditProductPrice: $isEditProductPrice, index: $index, purchaseUtil: $this->purchaseUtil);
                $index++;
            }

            $deletedUnusedPurchaseProducts = PurchaseProduct::where('purchase_id', $updatePurchase->id)
                ->where('delete_in_update', 1)
                ->get();

            if (count($deletedUnusedPurchaseProducts) > 0) {

                foreach ($deletedUnusedPurchaseProducts as $deletedPurchaseProduct) {

                    $storedProductId = $deletedPurchaseProduct->product_id;
                    $storedVariantId = $deletedPurchaseProduct->product_variant_id;
                    $deletedPurchaseProduct->delete();
                    // Adjust deleted product stock
                    $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);

                    if (isset($request->warehouse_count)) {

                        $this->productStockUtil->adjustWarehouseStock($storedProductId, $storedVariantId, $request->warehouse_id);
                    } else {

                        $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId, auth()->user()->branch_id);
                    }
                }
            }

            $purchaseProducts = DB::table('purchase_products')->where('purchase_id', $updatePurchase->id)->get();
            foreach ($purchaseProducts as $purchaseProduct) {

                $this->productStockUtil->adjustMainProductAndVariantStock($purchaseProduct->product_id, $purchaseProduct->product_variant_id);

                if (isset($request->warehouse_count)) {

                    $this->productStockUtil->addWarehouseProduct($purchaseProduct->product_id, $purchaseProduct->product_variant_id, $request->warehouse_id);
                    $this->productStockUtil->adjustWarehouseStock($purchaseProduct->product_id, $purchaseProduct->product_variant_id, $request->warehouse_id);
                } else {

                    $this->productStockUtil->addBranchProduct($purchaseProduct->product_id, $purchaseProduct->product_variant_id, auth()->user()->branch_id);
                    $this->productStockUtil->adjustBranchStock($purchaseProduct->product_id, $purchaseProduct->product_variant_id, auth()->user()->branch_id);
                }
            }

            if (isset($request->warehouse_count) && $request->warehouse_id != $storedWarehouseId) {

                foreach ($storePurchaseProducts as $PurchaseProduct) {

                    $this->productStockUtil->adjustWarehouseStock($PurchaseProduct->product_id, $PurchaseProduct->product_variant_id, $storedWarehouseId);
                }
            }

            // Update Purchase A/C Ledger
            $this->accountUtil->updateAccountLedger(
                voucher_type_id: 3,
                date: $request->date,
                account_id: $request->purchase_account_id,
                trans_id: $updatePurchase->id,
                amount: $request->total_purchase_amount,
                balance_type: 'debit'
            );

            // Update supplier ledger
            $this->supplierUtil->updateSupplierLedger(
                voucher_type_id: 1,
                supplier_id: $updatePurchase->supplier_id,
                previous_branch_id: auth()->user()->branch_id,
                new_branch_id: auth()->user()->branch_id,
                date: $request->date,
                trans_id: $updatePurchase->id,
                amount: $request->total_purchase_amount
            );

            // if ($editType == 'ordered') {

            //     $this->purchaseUtil->updatePoInvoiceQtyAndStatusPortion($updatePurchase);
            // }

            $adjustedPurchase = $this->purchaseUtil->adjustPurchaseInvoiceAmounts($updatePurchase);

            if ($adjustedPurchase->due > 0) {

                $this->supplierPaymentUtil->distributePurchaseDueAmount(request: $request, purchase: $adjustedPurchase, paymentInvoicePrefix: $paymentInvoicePrefix);
            }

            // Add user Log
            $this->userActivityLogUtil->addLog(
                action: 2,
                subject_type: $request->purchase_status == 3 ? 5 : 4,
                data_obj: $adjustedPurchase
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', ['Successfully purchase is updated', 'purchases']);

        return response()->json('Successfully purchase is updated');
    }

    // Get editable purchase
    public function editablePurchase($purchaseId, $editType)
    {
        if ($editType == 'purchased') {

            $purchase = Purchase::with(['warehouse', 'supplier', 'purchase_products', 'purchase_products.product', 'purchase_products.variant'])->where('id', $purchaseId)->first();

            return response()->json($purchase);
        } else {

            $purchase = Purchase::with(['warehouse', 'supplier', 'purchase_order_products', 'purchase_order_products.product', 'purchase_order_products.variant'])->where('id', $purchaseId)->first();

            return response()->json($purchase);
        }
    }

    // Get all supplier requested by ajax
    public function getAllSupplier()
    {
        $suppliers = Supplier::select('id', 'name', 'pay_term', 'pay_term_number', 'phone')->get();
        return response()->json($suppliers);
    }

    // Get all warehouse requested by ajax
    public function getAllUnit()
    {
        return Unit::select('id', 'name')->get();
    }

    // Get all warehouse requested by ajax
    public function getAllTax()
    {
        return DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();
    }

    // Search product by code
    public function searchProduct($product_code)
    {
        $__product_code = str_replace('~', '/', $product_code);

        $product = Product::with(['product_variants', 'tax', 'unit'])
            ->where('type', 1)
            ->where('product_code', $__product_code)
            ->where('status', 1)->first();

        if ($product) {

            $productBranch = DB::table('product_branches')->where('branch_id', auth()->user()->branch_id)->where('product_id', $product->id)->first();

            if (!$productBranch) {

                return response()->json(['errorMsg' => 'Product is not available in the Business Location']);
            }

            return response()->json(['product' => $product]);
        } else {

            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')
                ->where('variant_code', $__product_code)
                ->first();

            if ($variant_product) {

                $productBranch = DB::table('product_branches')->where('branch_id', auth()->user()->branch_id)
                    ->where('product_id', $variant_product->product_id)->first();

                if (!$productBranch) {

                    return response()->json(['errorMsg' => 'Product is not available in the Business Location']);
                }

                return response()->json(['variant_product' => $variant_product]);
            }
        }

        return $this->nameSearchUtil->nameSearching($__product_code);
    }

    // delete purchase method
    public function delete(Request $request, $purchaseId)
    {
        // get deleting purchase row
        $deletePurchase = purchase::with([
            'supplier',
            'purchase_products',
            'purchase_products.product',
            'purchase_products.variant',
            'purchase_products.purchaseSaleChains',
        ])->where('id', $purchaseId)->first();

        $supplier = DB::table('suppliers')->where('id', $deletePurchase->supplier_id)->first();
        //purchase payments
        $storedWarehouseId = $deletePurchase->warehouse_id;
        $storedPurchaseReturnAccountId = $deletePurchase->purchase_return ? $deletePurchase->purchase_return->purchase_return_account_id : null;
        $storedBranchId = $deletePurchase->branch_id;
        $storedPayments = $deletePurchase->purchase_payments;
        $storedPurchaseAccountId = $deletePurchase->purchase_account_id;
        $storePurchaseProducts = $deletePurchase->purchase_products;

        foreach ($deletePurchase->purchase_products as $purchase_product) {

            if (count($purchase_product->purchaseSaleChains) > 0) {

                $variant = $purchase_product->variant ? ' - ' . $purchase_product->variant->name : '';
                $product = $purchase_product->product->name . $variant;

                return response()->json("Can not delete is purchase. Mismatch between sold and purchase stock account method. Product: ${product}");
            }
        }

        foreach ($deletePurchase->purchase_products as $purchase_product) {

            $SupplierProduct = SupplierProduct::where('supplier_id', $deletePurchase->supplier_id)
                ->where('product_id', $purchase_product->product_id)
                ->where('product_variant_id', $purchase_product->product_variant_id)
                ->first();

            if ($SupplierProduct) {

                $SupplierProduct->label_qty -= $purchase_product->quantity;
                $SupplierProduct->save();
            }
        }

        // Add user Log
        $this->userActivityLogUtil->addLog(
            action: 3,
            subject_type: $deletePurchase->purchase_status == 3 ? 5 : 4,
            data_obj: $deletePurchase
        );

        $deletePurchase->delete();

        if ($storedPurchaseAccountId) {

            $this->accountUtil->adjustAccountBalance('debit', $storedPurchaseAccountId);
        }

        if ($storedPurchaseReturnAccountId) {

            $this->accountUtil->adjustAccountBalance('credit', $storedPurchaseReturnAccountId);
        }

        foreach ($storePurchaseProducts as $purchase_product) {

            $variant_id = $purchase_product->product_variant_id ? $purchase_product->product_variant_id : null;

            $this->productStockUtil->adjustMainProductAndVariantStock($purchase_product->product_id, $variant_id);

            if ($storedWarehouseId) {

                $this->productStockUtil->adjustWarehouseStock($purchase_product->product_id, $variant_id, $storedWarehouseId);
            } else {

                $this->productStockUtil->adjustBranchStock($purchase_product->product_id, $variant_id, $storedBranchId);
            }
        }

        if (count($storedPayments) > 0) {

            foreach ($storedPayments as $payment) {

                if ($payment->account_id) {

                    $this->accountUtil->adjustAccountBalance('debit', $payment->account_id);
                }
            }
        }

        $this->supplierUtil->adjustSupplierForPurchasePaymentDue($supplier->id);

        DB::statement('ALTER TABLE purchases AUTO_INCREMENT = 1');

        return response()->json('Successfully purchase is deleted');
    }

    // Add product modal view with data
    public function addProductModalView()
    {
        $units = DB::table('units')->select('id', 'name', 'code_name')->get();
        $warranties = DB::table('warranties')->select('id', 'name', 'type')->get();
        $taxes = DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();
        $categories = DB::table('categories')->where('parent_category_id', null)->orderBy('id', 'DESC')->get();
        $brands = DB::table('brands')->get();
        return view('purchases.ajax_view.add_product_modal_view', compact('units', 'warranties', 'taxes', 'categories', 'brands'));
    }

    // Add product from purchase
    public function addProduct(Request $request)
    {
        return $this->util->addQuickProductFromPurchase($request);
    }

    // Get recent added product which has been added from purchase
    public function getRecentProduct($product_id)
    {
        $product = Product::with(['tax', 'unit'])->where('id', $product_id)->first();
        $units = DB::table('units')->select('id', 'name')->get();

        return view('purchases.ajax_view.recent_product_view', compact('product', 'units'));
    }

    // Get quick supplier modal
    public function addQuickSupplierModal()
    {
        return view('purchases.ajax_view.add_quick_supplier');
    }

    // Change purchase status
    public function changeStatus(Request $request, $purchaseId)
    {
        $purchase = Purchase::where('id', $purchaseId)->first();

        $purchase->purchase_status = $request->purchase_status;

        $purchase->save();

        return response()->json('Successfully purchase status is changed.');
    }

    public function paymentModal($purchaseId)
    {
        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $purchase = Purchase::with(['supplier', 'branch', 'warehouse'])->where('id', $purchaseId)->first();

        return view('purchases.ajax_view.purchase_payment_modal', compact('purchase', 'accounts', 'methods'));
    }

    public function paymentStore(Request $request, $purchaseId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $generalSettings = config('generalSettings');

        $paymentInvoicePrefix = $generalSettings['prefix__purchase_payment'];

        $purchase = Purchase::where('id', $purchaseId)->first();

        if ($request->paying_amount > 0) {
            // Add purchase payment
            $addPurchasePaymentGetId = $this->purchaseUtil->addPurchasePaymentGetId(
                invoicePrefix: $paymentInvoicePrefix,
                request: $request,
                payingAmount: $request->paying_amount,
                invoiceId: str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, '0', STR_PAD_LEFT),
                purchase: $purchase,
                supplier_payment_id: null
            );

            // Add Bank/Cash-In-Hand A/C Ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 11,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $addPurchasePaymentGetId,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );

            // Add supplier ledger
            $this->supplierUtil->addSupplierLedger(
                voucher_type_id: 3,
                supplier_id: $purchase->supplier_id,
                branch_id: auth()->user()->branch_id,
                date: $request->date,
                trans_id: $addPurchasePaymentGetId,
                amount: $request->paying_amount,
            );

            $purchasePayment = DB::table('purchase_payments')
                ->where('purchase_payments.id', $addPurchasePaymentGetId)
                ->leftJoin('suppliers', 'purchase_payments.supplier_id', 'suppliers.id')
                ->leftJoin('payment_methods', 'purchase_payments.payment_method_id', 'payment_methods.id')
                ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
                ->select(
                    'purchase_payments.invoice_id as voucher_no',
                    'purchase_payments.date',
                    'purchase_payments.paid_amount',
                    'suppliers.name as supplier',
                    'suppliers.phone',
                    'payment_methods.name as method',
                    'purchases.invoice_id as agp',
                )->first();

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 28, data_obj: $purchasePayment);

            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
        }

        return response()->json('Successfully payment is added.');
    }

    public function paymentEdit($paymentId)
    {
        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $payment = PurchasePayment::with(['purchase', 'purchase.branch', 'purchase.warehouse', 'purchase.supplier'])
            ->where('id', $paymentId)->first();

        return view('purchases.ajax_view.purchase_payment_edit_modal', compact('payment', 'accounts', 'methods'));
    }

    public function paymentUpdate(Request $request, $paymentId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        if ($request->paying_amount > 0) {

            $updatePurchasePayment = PurchasePayment::with(
                'account',
                'purchase.purchase_return',
            )->where('id', $paymentId)->first();

            $purchase = Purchase::where('id', $updatePurchasePayment->purchase_id)->first();

            $this->purchaseUtil->updatePurchasePayment($request, $updatePurchasePayment);

            if ($updatePurchasePayment->supplier_payment_id == null) {

                // Update Bank/Cash-in-hand A/C Ledger
                $this->accountUtil->updateAccountLedger(
                    voucher_type_id: 11,
                    date: $request->date,
                    account_id: $request->account_id,
                    trans_id: $updatePurchasePayment->id,
                    amount: $request->paying_amount,
                    balance_type: 'debit'
                );

                // Update supplier ledger
                $this->supplierUtil->updateSupplierLedger(
                    voucher_type_id: 3,
                    supplier_id: $purchase->supplier_id,
                    previous_branch_id: auth()->user()->branch_id,
                    new_branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $updatePurchasePayment->id,
                    amount: $request->paying_amount
                );
            }

            $purchasePayment = DB::table('purchase_payments')
                ->where('purchase_payments.id', $updatePurchasePayment->id)
                ->leftJoin('suppliers', 'purchase_payments.supplier_id', 'suppliers.id')
                ->leftJoin('payment_methods', 'purchase_payments.payment_method_id', 'payment_methods.id')
                ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
                ->select(
                    'purchase_payments.invoice_id as voucher_no',
                    'purchase_payments.date',
                    'purchase_payments.paid_amount',
                    'suppliers.name as supplier',
                    'suppliers.phone',
                    'payment_methods.name as method',
                    'purchases.invoice_id as agp',
                )->first();

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 28, data_obj: $purchasePayment);

            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
        }

        return response()->json('Successfully payment is updated.');
    }

    public function returnPaymentModal($purchaseId)
    {
        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $purchase = Purchase::with(['supplier', 'branch', 'warehouse'])->where('id', $purchaseId)->first();

        return view('purchases.ajax_view.purchase_return_payment', compact('purchase', 'accounts', 'methods'));
    }

    public function returnPaymentStore(Request $request, $purchaseId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $purchase = Purchase::with(['purchase_return'])->where('id', $purchaseId)->first();

        if ($request->paying_amount > 0) {

            $purchaseReturnPaymentGetId = $this->purchaseUtil->purchaseReturnPaymentGetId(
                request: $request,
                purchase: $purchase,
                supplier_payment_id: null
            );

            // Add Bank/Cash-In-Hand A/C Ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 17,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $purchaseReturnPaymentGetId,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );

            // Add supplier ledger
            $this->supplierUtil->addSupplierLedger(
                voucher_type_id: 4,
                supplier_id: $purchase->supplier_id,
                branch_id: auth()->user()->branch_id,
                date: $request->date,
                trans_id: $purchaseReturnPaymentGetId,
                amount: $request->paying_amount,
            );

            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);

            // update purchase return
            if ($purchase->purchase_return) {

                $this->purchaseReturnUtil->adjustPurchaseReturnAmounts($purchase->purchase_return);
            }
        }

        return response()->json('Successfully payment is added.');
    }

    public function returnPaymentEdit($paymentId)
    {
        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        $payment = PurchasePayment::with(['purchase', 'purchase.branch', 'purchase.warehouse', 'purchase.supplier'])
            ->where('id', $paymentId)->first();

        return view('purchases.ajax_view.purchase_return_payment_edit', compact('payment', 'accounts', 'methods'));
    }

    public function returnPaymentUpdate(Request $request, $paymentId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        if ($request->paying_amount > 0) {

            $updatePurchasePayment = PurchasePayment::where('id', $paymentId)->first();

            $purchase = Purchase::with('purchase_return')
                ->where('id', $updatePurchasePayment->purchase_id)->first();

            $this->purchaseUtil->updatePurchaseReturnPayment($request, $updatePurchasePayment);

            if ($updatePurchasePayment->supplier_payment_id == null) {

                // Update Bank/Cash-in-hand A/C Ledger
                $this->accountUtil->updateAccountLedger(
                    voucher_type_id: 17,
                    date: $request->date,
                    account_id: $request->account_id,
                    trans_id: $updatePurchasePayment->id,
                    amount: $request->paying_amount,
                    balance_type: 'debit'
                );

                // Update supplier ledger
                $this->supplierUtil->updateSupplierLedger(
                    voucher_type_id: 4,
                    supplier_id: $purchase->supplier_id,
                    previous_branch_id: auth()->user()->branch_id,
                    new_branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $updatePurchasePayment->id,
                    amount: $request->paying_amount
                );
            }

            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);

            // update purchase return
            if ($purchase->purchase_return) {

                $this->purchaseReturnUtil->adjustPurchaseReturnAmounts($purchase->purchase_return);
            }
        }

        return response()->json('Successfully payment is updated.');
    }

    //Get purchase wise payment list
    public function paymentList($purchaseId)
    {
        $purchase = Purchase::with([
            'supplier',
            'purchase_payments',
            'purchase_payments.account',
            'purchase_payments.paymentMethod',
        ])->where('id', $purchaseId)->first();

        return view('purchases.ajax_view.view_payment_list', compact('purchase'));
    }

    public function paymentDetails($paymentId)
    {
        $payment = PurchasePayment::with(
            'paymentMethod',
            'purchase',
            'purchase.branch',
            'purchase.warehouse',
            'purchase.supplier'
        )->where('id', $paymentId)->first();

        return view('purchases.ajax_view.payment_details', compact('payment'));
    }

    // Delete purchase payment
    public function paymentDelete(Request $request, $paymentId)
    {
        $deletePurchasePayment = PurchasePayment::with('account', 'purchase', 'purchase.purchase_return')
            ->where('id', $paymentId)
            ->first();

        if (!is_null($deletePurchasePayment)) {

            $storedAccountId = $deletePurchasePayment->account_id;
            if ($deletePurchasePayment->attachment != null) {

                if (file_exists(public_path('uploads/payment_attachment/' . $deletePurchasePayment->attachment))) {

                    unlink(public_path('uploads/payment_attachment/' . $deletePurchasePayment->attachment));
                }
            }

            //Update Supplier due
            if ($deletePurchasePayment->payment_type == 1) {

                $storedSupplierId = $deletePurchasePayment->purchase->supplier_id;
                $storedPurchaseId = $deletePurchasePayment->purchase_id;

                $purchasePayment = DB::table('purchase_payments')
                    ->where('purchase_payments.id', $deletePurchasePayment->id)
                    ->leftJoin('suppliers', 'purchase_payments.supplier_id', 'suppliers.id')
                    ->leftJoin('payment_methods', 'purchase_payments.payment_method_id', 'payment_methods.id')
                    ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
                    ->select(
                        'purchase_payments.invoice_id as voucher_no',
                        'purchase_payments.date',
                        'purchase_payments.paid_amount',
                        'suppliers.name as supplier',
                        'suppliers.phone',
                        'payment_methods.name as method',
                        'purchases.invoice_id as agp',
                    )->first();

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 28, data_obj: $purchasePayment);

                $deletePurchasePayment->delete();

                if ($storedPurchaseId) {

                    $purchase = Purchase::where('id', $storedPurchaseId)->first();
                    $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
                }

                $this->supplierUtil->adjustSupplierForPurchasePaymentDue($storedSupplierId);
            } else {
                if ($deletePurchasePayment->purchase) {

                    $storedPurchase = $deletePurchasePayment->purchase;
                    $storedPurchaseReturn = $deletePurchasePayment->purchase->purchase_return;
                    $deletePurchasePayment->delete();

                    // update purchase return
                    if ($storedPurchaseReturn) {

                        $this->purchaseReturnUtil->adjustPurchaseReturnAmounts($storedPurchaseReturn);
                    }

                    $this->purchaseUtil->adjustPurchaseInvoiceAmounts($storedPurchase);
                    $this->supplierUtil->adjustSupplierForPurchasePaymentDue($storedPurchase->supplier_id);
                } else {

                    $purchaseReturn = PurchaseReturn::where('id', $deletePurchasePayment->supplier_return->id)->first();
                    $purchaseReturn->total_return_due_received -= $deletePurchasePayment->paid_amount;
                    $purchaseReturn->total_return_due += $deletePurchasePayment->paid_amount;
                    $purchaseReturn->save();
                    $deletePurchasePayment->delete();
                    $this->supplierUtil->adjustSupplierForPurchasePaymentDue($purchaseReturn->supplier_id);
                }
            }

            if ($storedAccountId) {

                $this->accountUtil->adjustAccountBalance('debit', $storedAccountId);
            }
        }

        DB::statement('ALTER TABLE purchase_payments AUTO_INCREMENT = 1');

        return response()->json('Successfully payment is deleted.');
    }

    //Show Change status modal
    public function settings()
    {
        return view('purchases.settings.index');
    }

    //Show Change status modal
    public function settingsStore(Request $request, GeneralSettingServiceInterface $generalSettingService)
    {
        $settings = [
            'purchase__is_edit_pro_price' => isset($request->is_edit_pro_price) ? 1 : 0,
            'purchase__is_enable_status' => isset($request->is_enable_status) ? 1 : 0,
            'purchase__is_enable_lot_no' => isset($request->is_enable_lot_no) ? 1 : 0,
        ];
        $generalSettingService->updateAndSync($settings);

        return response()->json('Purchase settings updated successfully.');
    }
}
