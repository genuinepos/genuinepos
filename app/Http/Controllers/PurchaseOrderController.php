<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Utils\AccountUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\ProductUtil;
use App\Utils\PurchaseOrderProductUtil;
use App\Utils\PurchaseOrderUtil;
use App\Utils\PurchaseUtil;
use App\Utils\SupplierPaymentUtil;
use App\Utils\SupplierUtil;
use App\Utils\UserActivityLogUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function __construct(
        private PurchaseUtil $purchaseUtil,
        private PurchaseOrderUtil $purchaseOrderUtil,
        private PurchaseOrderProductUtil $purchaseOrderProductUtil,
        private Util $util,
        private SupplierUtil $supplierUtil,
        private SupplierPaymentUtil $supplierPaymentUtil,
        private ProductUtil $productUtil,
        private AccountUtil $accountUtil,
        private InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        private UserActivityLogUtil $userActivityLogUtil
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
        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();
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

        $purchaseAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->where('accounts.account_type', 3)
            ->get(['accounts.id', 'accounts.name']);

        $taxes = DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();
        $units = DB::table('units')->select('id', 'name')->get();
        $suppliers = DB::table('suppliers')->select('id', 'name', 'phone', 'pay_term', 'pay_term_number')->get();

        return view('purchases.orders.create', compact('methods', 'accounts', 'purchaseAccounts', 'taxes', 'units', 'suppliers'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'supplier_id' => 'required',
            'invoice_id' => 'sometimes|unique:purchases,invoice_id',
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

        if (isset($request->warehouse_count)) {

            $this->validate($request, ['warehouse_id' => 'required']);
        }

        if (! isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Product table is empty.']);
        } elseif (count($request->product_ids) > 60) {

            return response()->json(['errorMsg' => 'Purchase invoice items must be less than 60 or equal.']);
        }

        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $paymentInvoicePrefix = $generalSettings['prefix__purchase_payment'];
            $isEditProductPrice = $generalSettings['purchase__is_edit_pro_price'];

            $__purchaseOrderIdPrefix = 'PO';
            $addOrder = $this->purchaseOrderUtil->addPurchaseOrder(request: $request, invoiceVoucherRefIdUtil: $this->invoiceVoucherRefIdUtil, purchaseOrderIdPrefix: $__purchaseOrderIdPrefix);

            $this->purchaseOrderProductUtil->addPurchaseOrderProduct(request: $request, orderId: $addOrder->id, isEditProductPrice: $isEditProductPrice);

            if ($request->paying_amount > 0) {

                // Add payment
                $addPurchasePaymentGetId = $this->purchaseUtil->addPurchasePaymentGetId(
                    invoicePrefix: $paymentInvoicePrefix,
                    request: $request,
                    payingAmount: $request->paying_amount,
                    invoiceId: str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, '0', STR_PAD_LEFT),
                    purchase: $addOrder,
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

                // Add supplier ledger for payment
                $this->supplierUtil->addSupplierLedger(
                    voucher_type_id: 3,
                    supplier_id: $request->supplier_id,
                    branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $addPurchasePaymentGetId,
                    amount: $request->paying_amount,
                );
            }

            $adjustedPurchase = $this->purchaseUtil->adjustPurchaseInvoiceAmounts($addOrder);

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 1, subject_type: 5, data_obj: $addOrder);

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
