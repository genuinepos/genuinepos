<?php

namespace App\Http\Controllers\Purchases;

use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CodeGenerationService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Purchases\PurchaseOrderService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Purchases\PurchaseOrderProductService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class PurchaseOrderController extends Controller
{
    public function __construct(
        private PurchaseOrderService $purchaseOrderService,
        private PurchaseOrderProductService $purchaseOrderProductService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchSettingService $branchSettingService,
        private PaymentMethodService $paymentMethodService,
        private DayBookService $dayBookService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private AccountLedgerService $accountLedgerService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('purchase_all')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseOrderUtil->poListTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $suppliers = DB::table('suppliers')->get(['id', 'name', 'phone']);

        return view('purchases.orders.index', compact('branches', 'suppliers'));
    }

    public function show($id)
    {
        $order = Purchase::with([
            'warehouse',
            'branch',
            'supplier',
            'admin',
            'purchase_order_products',
            'purchase_order_products.receives',
            'purchase_order_products.product',
            'purchase_order_products.product.warranty',
            'purchase_order_products.variant',
            'purchase_payments',
        ])->where('id', $id)->first();

        return view('purchases.orders.ajax_view.show', compact('order'));
    }

    public function printSupplierCopy($id)
    {
        $purchase = Purchase::with([
            'branch',
            'supplier',
            'admin',
            'purchase_order_products',
            'purchase_products.product',
            'purchase_products.variant',
        ])->where('id', $id)->first();

        return view('purchases.ajax_view.print_supplier_copy', compact('purchase'));
    }

    public function create()
    {
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

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.orders.create', compact('methods', 'accounts', 'purchaseAccounts', 'taxAccounts', 'supplierAccounts'));
    }

    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'supplier_account_id' => 'required',
            'date' => 'required|date',
            'delivery_date' => 'required|date',
            'payment_method_id' => 'required',
            'purchase_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'purchase_account_id.required' => 'Purchase A/c is required.',
            'account_id.required' => 'Credit A/c is required.',
            'payment_method_id.required' => 'Payment method field is required.',
            'supplier_id.required' => 'Supplier is required.',
        ]);

        $restrictions = $this->purchaseOrderService->restrictions($request);
        if ($restrictions['pass'] == false) {

            return response()->json(['errorMsg' => $restrictions['msg']]);
        }

        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $invoicePrefix = isset($branchSetting) && $branchSetting?->purchase_order_prefix ? $branchSetting?->purchase_order_prefix : 'PO';
            $paymentVoucherPrefix = isset($branchSetting) && $branchSetting?->payment_voucher_prefix ? $branchSetting?->payment_voucher_prefix : $generalSettings['prefix__payment'];

            $addPurchaseOrder = $this->purchaseOrderService->addPurchaseOrder(request: $request, codeGenerator: $codeGenerator, invoicePrefix: $invoicePrefix);

            // Add Day Book entry for Purchase
            $this->dayBookService->addDayBook(voucherTypeId: 5, date: $request->date, accountId: $request->supplier_account_id, transId: $addPurchaseOrder->id, amount: $request->total_ordered_amount, amountType: 'credit');

            $index = 0;
            foreach ($request->product_ids as $productId) {

                $addPurchaseOrderProduct = $this->purchaseOrderProductService->addPurchaseOrderProduct(request: $request, purchaseOrderId: $addPurchaseOrder->id, index: $index);

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
                $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, note: $request->payment_note);

                //Add Credit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 9, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'credit');
            }

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 1, subject_type: 5, data_obj: $addPurchaseOrder);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        $order = Purchase::with([
            'branch',
            'supplier',
            'admin:id,prefix,name,last_name',
            'purchase_order_products',
            'purchase_order_products.product',
            'purchase_order_products.product.warranty',
            'purchase_order_products.variant',
            'purchase_payments',
        ])->where('id', $addOrder->id)->first();

        if ($request->action == 2) {

            return response()->json(['successMsg' => 'Successfully purchase order is created.']);
        } else {

            return view('purchases.save_and_print_template.print_purchase_order', compact('order'));
        }
    }

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

        if (! isset($request->product_ids)) {

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

                if (! $SupplierProduct) {

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

    // Purchase edit view
    public function edit($id)
    {
        $purchaseAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->where('accounts.account_type', 3)
            ->get(['accounts.id', 'accounts.name']);

        $order = Purchase::with(['warehouse', 'supplier', 'purchase_products', 'purchase_order_products.product', 'purchase_order_products.variant'])->where('id', $id)->first();

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();
        $taxes = DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();
        $units = DB::table('units')->select('id', 'name')->get();

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

        return view('purchases.orders.edit', compact('order', 'purchaseAccounts', 'order', 'taxes', 'units', 'methods', 'accounts'));
    }
}
