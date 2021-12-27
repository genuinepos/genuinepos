<?php

namespace App\Http\Controllers;

use DB;
use App\Utils\Util;
use App\Models\Unit;
use App\Models\Product;
use App\Models\CashFlow;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Utils\AccountUtil;
use App\Utils\PurchaseUtil;
use App\Utils\SupplierUtil;
use Illuminate\Http\Request;
use App\Utils\NameSearchUtil;
use App\Models\ProductVariant;
use App\Models\PurchaseReturn;
use App\Models\SupplierLedger;
use App\Models\PurchasePayment;
use App\Models\PurchaseProduct;
use App\Models\SupplierProduct;
use App\Utils\ProductStockUtil;
use App\Models\PurchaseOrderProduct;
use App\Utils\InvoiceVoucherRefIdUtil;

class PurchaseController extends Controller
{
    protected $purchaseUtil;
    protected $nameSearchUtil;
    protected $util;
    protected $supplierUtil;
    protected $productStockUtil;
    protected $accountUtil;
    protected $invoiceVoucherRefIdUtil;
    public function __construct(
        NameSearchUtil $nameSearchUtil,
        PurchaseUtil $purchaseUtil,
        Util $util,
        SupplierUtil $supplierUtil,
        ProductStockUtil $productStockUtil,
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil
    ) {
        $this->nameSearchUtil = $nameSearchUtil;
        $this->purchaseUtil = $purchaseUtil;
        $this->util = $util;
        $this->supplierUtil = $supplierUtil;
        $this->productStockUtil = $productStockUtil;
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function index_v2(Request $request)
    {
        if (auth()->user()->permission->purchase['purchase_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            return $this->purchaseUtil->purchaseListTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $suppliers = DB::table('suppliers')->select('id', 'name', 'phone')->get();
        return view('purchases.index_v2', compact('branches', 'suppliers'));
    }

    public function purchaseProductList(Request $request)
    {
        if (auth()->user()->permission->purchase['purchase_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            return $this->purchaseUtil->purchaseProductListTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $suppliers = DB::table('suppliers')->get(['id', 'name', 'phone']);
        $categories = DB::table('categories')->where('parent_category_id', NULL)->get(['id', 'name']);
        return view('purchases.purchase_product_list', compact('branches', 'suppliers', 'categories'));
    }

    public function poList(Request $request)
    {
        if (auth()->user()->permission->purchase['purchase_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            return $this->purchaseUtil->poListTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $suppliers = DB::table('suppliers')->get(['id', 'name', 'phone']);
        return view('purchases.po_list', compact('branches', 'suppliers'));
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

    public function showOrder($purchaseId)
    {
        $purchase = Purchase::with([
            'warehouse',
            'branch',
            'supplier',
            'admin',
            'purchase_order_products',
            'purchase_products.product',
            'purchase_products.product.warranty',
            'purchase_products.variant',
            'purchase_payments',
        ])->where('id', $purchaseId)->first();
        return view('purchases.ajax_view.order_details', compact('purchase'));
    }

    public function printSupplierCopy($purchaseId)
    {
        $purchase = Purchase::with([
            'branch',
            'supplier',
            'admin',
            'purchase_order_products',
            'purchase_products.product',
            'purchase_products.variant',
        ])->where('id', $purchaseId)->first();
        return view('purchases.ajax_view.print_supplier_copy', compact('purchase'));
    }

    public function create()
    {
        if (auth()->user()->permission->purchase['purchase_add'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $warehouses = DB::table('warehouses')->where('branch_id', auth()->user()->branch_id)->get();
        return view('purchases.create', compact('warehouses'));
    }

    // add purchase method
    public function store(Request $request)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_invoice'];
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_payment'];
        $isEditProductPrice = json_decode($prefixSettings->purchase, true)['is_edit_pro_price'];

        if (isset($request->warehouse_id)) {
            $this->validate($request, ['warehouse_id' => 'required']);
        }

        $this->validate($request, ['supplier_id' => 'required']);

        if (!isset($request->product_ids)) {
            return response()->json(['errorMsg' => 'Product table is empty.']);
        } elseif (count($request->product_ids) > 60) {
            return response()->json(['errorMsg' => 'Purchase invoice items must be less than 60 or equal.']);
        }

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $quantities = $request->quantities;
        $unit_costs_with_discount = $request->unit_costs_with_discount;
        $net_unit_costs = $request->net_unit_costs;
        $profits = $request->profits;
        $selling_prices = $request->selling_prices;

        // Add supplier product
        $i = 0;
        foreach ($product_ids as $product_id) {
            $variant_id = $variant_ids[$i] != 'noid' ? $variant_ids[$i] : NULL;
            $SupplierProduct = SupplierProduct::where('supplier_id', $request->supplier_id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)
                ->first();
            if (!$SupplierProduct) {
                $addSupplierProduct = new SupplierProduct();
                $addSupplierProduct->supplier_id = $request->supplier_id;
                $addSupplierProduct->product_id = $product_id;
                $addSupplierProduct->product_variant_id = $variant_id;
                $addSupplierProduct->label_qty = $quantities[$i];
                $addSupplierProduct->save();
            } else {
                $SupplierProduct->label_qty = $SupplierProduct->label_qty + $quantities[$i];
                $SupplierProduct->save();
            }
            $i++;
        }

        $updateLastCreated = Purchase::where('is_last_created', 1)->select('id', 'is_last_created')->first();
        if ($updateLastCreated) {
            $updateLastCreated->is_last_created = 0;
            $updateLastCreated->save();
        }

        // add purchase total information
        $addPurchase = new Purchase();
        $addPurchase->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchases'), 5, "0", STR_PAD_LEFT);
        $addPurchase->warehouse_id = $request->warehouse_id ? $request->warehouse_id : NULL;
        $addPurchase->branch_id = auth()->user()->branch_id;
        $addPurchase->supplier_id = $request->supplier_id;
        $addPurchase->pay_term = $request->pay_term;
        $addPurchase->pay_term_number = $request->pay_term_number;
        $addPurchase->admin_id = auth()->user()->id;
        $addPurchase->total_item = $request->total_item;
        $addPurchase->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $addPurchase->order_discount_type = $request->order_discount_type;
        $addPurchase->order_discount_amount = $request->order_discount_amount;
        $addPurchase->purchase_tax_percent = $request->purchase_tax_percent ? $request->purchase_tax_percent : 0.00;
        $addPurchase->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0.00;
        $addPurchase->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $addPurchase->net_total_amount = $request->net_total_amount;
        $addPurchase->total_purchase_amount = $request->total_purchase_amount;
        $addPurchase->paid = $request->paying_amount;
        $addPurchase->due = $request->purchase_due;
        $addPurchase->shipment_details = $request->shipment_details;
        $addPurchase->purchase_note = $request->purchase_note;
        $addPurchase->purchase_status = $request->purchase_status;
        $addPurchase->is_purchased = $request->purchase_status == 1 ? 1 : 0;
        $addPurchase->po_qty = $request->purchase_status == 1 ? $request->total_qty : 0;
        $addPurchase->po_pending_qty = $request->purchase_status == 1 ? $request->total_qty : 0;
        $addPurchase->po_receiving_status = $request->purchase_status == 1 ? NULL : 'Pending';
        $addPurchase->date = $request->date;
        $addPurchase->delivery_date = $request->delivery_date;
        $addPurchase->report_date = date('Y-m-d', strtotime($request->date));
        $addPurchase->time = date('h:i:s a');
        $addPurchase->month = date('F');
        $addPurchase->year = date('Y');
        $addPurchase->is_last_created = 1;

        if ($request->hasFile('attachment')) {
            $purchaseAttachment = $request->file('attachment');
            $purchaseAttachmentName = uniqid() . '-' . '.' . $purchaseAttachment->getClientOriginalExtension();
            $purchaseAttachment->move(public_path('uploads/purchase_attachment/'), $purchaseAttachmentName);
            $addPurchase->attachment = $purchaseAttachmentName;
        }
        $addPurchase->save();

        // add purchase or purchase order product

        if ($request->purchase_status == 1) {
            $this->purchaseUtil->addPurchaseProduct($request, $isEditProductPrice, $addPurchase->id);
        } else {
            $this->purchaseUtil->addPurchaseOrderProduct($request, $isEditProductPrice, $addPurchase->id);
        }

        // Add supplier ledger
        $addSupplierLedger = new SupplierLedger();
        $addSupplierLedger->supplier_id = $request->supplier_id;
        $addSupplierLedger->purchase_id = $addPurchase->id;
        $addSupplierLedger->report_date = date('Y-m-d', strtotime($request->date));
        $addSupplierLedger->save();

        // Add purchase payment
        if ($request->paying_amount > 0) {
            $addPurchasePayment = new PurchasePayment();
            $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : '') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT);
            $addPurchasePayment->purchase_id = $addPurchase->id;
            $addPurchasePayment->account_id = $request->account_id;
            $addPurchasePayment->pay_mode = $request->payment_method;
            $addPurchasePayment->paid_amount = $request->paying_amount;
            $addPurchasePayment->date = $request->date;
            $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
            $addPurchasePayment->month = date('F');
            $addPurchasePayment->year = date('Y');
            $addPurchasePayment->note = $request->payment_note;
            $addPurchasePayment->is_advanced = $request->purchase_status == 3 ? 1 : 0;

            if ($request->payment_method == 'Card') {
                $addPurchasePayment->card_no = $request->card_no;
                $addPurchasePayment->card_holder = $request->card_holder_name;
                $addPurchasePayment->card_transaction_no = $request->card_transaction_no;
                $addPurchasePayment->card_type = $request->card_type;
                $addPurchasePayment->card_month = $request->month;
                $addPurchasePayment->card_year = $request->year;
                $addPurchasePayment->card_secure_code = $request->secure_code;
            } elseif ($request->payment_method == 'Cheque') {
                $addPurchasePayment->cheque_no = $request->cheque_no;
            } elseif ($request->payment_method == 'Bank-Transfer') {
                $addPurchasePayment->account_no = $request->account_no;
            } elseif ($request->payment_method == 'Custom') {
                $addPurchasePayment->transaction_no = $request->transaction_no;
            }
            $addPurchasePayment->admin_id = auth()->user()->id;
            $addPurchasePayment->save();

            if ($request->account_id) {
                // Add cash flow
                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->debit = $request->paying_amount;
                $addCashFlow->purchase_payment_id = $addPurchasePayment->id;
                $addCashFlow->transaction_type = 3;
                $addCashFlow->cash_type = 1;
                $addCashFlow->date = $request->date;
                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
                $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
                $addCashFlow->save();
            }

            // Add supplier ledger
            $addSupplierLedger = new SupplierLedger();
            $addSupplierLedger->supplier_id = $request->supplier_id;
            $addSupplierLedger->purchase_payment_id = $addPurchasePayment->id;
            $addSupplierLedger->row_type = 2;
            $addSupplierLedger->report_date = date('Y-m-d', strtotime($request->date));
            $addSupplierLedger->save();
        }

        // update main product and variant price
        if ($request->purchase_status == 1) {
            $loop = 0;
            foreach ($product_ids as $productId) {
                $variant_id = $variant_ids[$loop] != 'noid' ? $variant_ids[$loop] : NULL;
                $__xMargin = isset($request->profits) ? $profits[$loop] : 0;
                $__sale_price = isset($request->selling_prices) ? $selling_prices[$loop] : 0;
                $this->purchaseUtil->updateProductAndVariantPrice($productId, $variant_id, $unit_costs_with_discount[$loop], $net_unit_costs[$loop], $__xMargin, $__sale_price, $isEditProductPrice);
                $loop++;
            }
        }

        if ($request->purchase_status == 1) {
            $__index = 0;
            foreach ($product_ids as $productId) {
                $variant_id = $variant_ids[$__index] != 'noid' ? $variant_ids[$__index] : NULL;
                $this->productStockUtil->adjustMainProductAndVariantStock($productId, $variant_id);
                if (isset($request->warehouse_id)) {
                    $this->productStockUtil->addWarehouseProduct($productId, $variant_id, $request->warehouse_id);
                    $this->productStockUtil->adjustWarehouseStock($productId, $variant_id, $request->warehouse_id);
                } else {
                    $this->productStockUtil->addBranchProduct($productId, $variant_id, auth()->user()->branch_id);
                    $this->productStockUtil->adjustBranchStock($productId, $variant_id, auth()->user()->branch_id);
                }
                $__index++;
            }
        }

        $this->supplierUtil->adjustSupplierForSalePaymentDue($request->supplier_id);
        if ($request->action == 2) {
            return response()->json(['successMsg' => 'Successfully purchase is created.']);
        } else {
            if ($request->purchase_status == 3) {
                $purchase = Purchase::with([
                    'warehouse:id,warehouse_name,warehouse_code',
                    'branch',
                    'supplier',
                    'admin:id,prefix,name,last_name',
                    'purchase_order_products',
                    'purchase_products.product',
                    'purchase_products.product.warranty',
                    'purchase_products.variant',
                    'purchase_payments',
                ])->where('id', $addPurchase->id)->first();
                return view('purchases.save_and_print_template.print_order', compact('purchase'));
            } else {
                $purchase = Purchase::with([
                    'warehouse:id,warehouse_name,warehouse_code',
                    'branch',
                    'supplier',
                    'admin:id,prefix,name,last_name',
                    'purchase_products',
                    'purchase_products.product',
                    'purchase_products.product.warranty',
                    'purchase_products.variant',
                    'purchase_payments',
                ])->where('id', $addPurchase->id)->first();
                return view('purchases.save_and_print_template.print_purchase', compact('purchase'));
            }
        }
    }

    // update purchase method
    public function update(Request $request, $editType)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_invoice'];
        $isEditProductPrice = json_decode($prefixSettings->purchase, true)['is_edit_pro_price'];

        if (isset($request->warehouse_id)) {
            $this->validate($request, ['warehouse_id' => 'required']);
        }

        if (!isset($request->product_ids)) {
            return response()->json(['errorMsg' => 'Product table is empty.']);
        }

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $quantities = $request->quantities;
        $unit_costs_with_discount = $request->unit_costs_with_discount;
        $net_unit_costs = $request->net_unit_costs;
        $profits = $request->profits;
        $selling_prices = $request->selling_prices;

        // get updatable purchase row
        $updatePurchase = purchase::with(['purchase_products', 'purchase_order_products', 'ledger'])
            ->where('id', $request->id)->first();
        $storedWarehouseId = $updatePurchase->warehouse_id;
        $storePurchaseProducts = $updatePurchase->purchase_products;

        // update product and variant quantity for adjustment
        foreach ($updatePurchase->purchase_products as $purchase_product) {
            $SupplierProduct = SupplierProduct::where('supplier_id', $updatePurchase->supplier_id)
                ->where('product_id', $purchase_product->product_id)
                ->where('product_variant_id', $purchase_product->product_variant_id)
                ->first();

            if ($SupplierProduct) {
                $SupplierProduct->label_qty -= (float)$purchase_product->quantity;
                $SupplierProduct->save();
            }
        }

        $purchaseOrOrderProducts = $editType == 'purchased' ? $updatePurchase->purchase_products : $updatePurchase->purchase_order_products;
        foreach ($purchaseOrOrderProducts as $purchaseOrOrderProduct) {
            $purchaseOrOrderProduct->delete_in_update = 1;
            $purchaseOrOrderProduct->save();
        }

        // update supplier product
        $i = 0;
        foreach ($product_ids as $product_id) {
            $variant_id = $variant_ids[$i] != 'noid' ? $variant_ids[$i] : NULL;
            $SupplierProduct = SupplierProduct::where('supplier_id', $updatePurchase->supplier_id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)
                ->first();
            if (!$SupplierProduct) {
                $addSupplierProduct = new SupplierProduct();
                $addSupplierProduct->supplier_id = $updatePurchase->supplier_id;
                $addSupplierProduct->product_id = $product_id;
                $addSupplierProduct->product_variant_id = $variant_id;
                $addSupplierProduct->label_qty = $quantities[$i];
                $addSupplierProduct->save();
            } else {
                $SupplierProduct->label_qty = $SupplierProduct->label_qty + $quantities[$i];
                $SupplierProduct->save();
            }
            $i++;
        }

        $updatePurchase->warehouse_id = isset($request->warehouse_id) ? $request->warehouse_id : NULL;

        // update purchase total information
        $updatePurchase->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . date('my') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchases'), 5, "0", STR_PAD_LEFT);;
        $updatePurchase->pay_term = $request->pay_term;
        $updatePurchase->pay_term_number = $request->pay_term_number;
        $updatePurchase->invoice_id = $request->invoice_id;
        $updatePurchase->admin_id = auth()->user()->id;
        $updatePurchase->total_item = $request->total_item;
        $updatePurchase->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $updatePurchase->order_discount_type = $request->order_discount_type;
        $updatePurchase->order_discount_amount = $request->order_discount_amount;
        $updatePurchase->purchase_tax_percent = $request->purchase_tax ? $request->purchase_tax : 0.00;
        $updatePurchase->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0.00;
        $updatePurchase->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $updatePurchase->net_total_amount = $request->net_total_amount;
        $updatePurchase->total_purchase_amount = $request->total_purchase_amount;
        $updatePurchase->shipment_details = $request->shipment_details;
        $updatePurchase->purchase_note = $request->purchase_note;
        $updatePurchase->purchase_status = $request->purchase_status;
        $updatePurchase->date = $request->date;
        $updatePurchase->report_date = date('Y-m-d', strtotime($request->date));

        if ($request->hasFile('attachment')) {
            if ($updatePurchase->attachment != null) {
                if (file_exists(public_path('uploads/purchase_attachment/' . $updatePurchase->attachment))) {
                    unlink(public_path('uploads/purchase_attachment/' . $updatePurchase->attachment));
                }
            }
            $purchaseAttachment = $request->file('attachment');
            $purchaseAttachmentName = uniqid() . '-' . '.' . $purchaseAttachment->getClientOriginalExtension();
            $purchaseAttachment->move(public_path('uploads/purchase_attachment/'), $purchaseAttachmentName);
            $updatePurchase->attachment = $purchaseAttachmentName;
        }
        $updatePurchase->save();
        if ($updatePurchase->ledger) {
            $updatePurchase->ledger->report_date = $updatePurchase->report_date;
            $updatePurchase->ledger->save();
        } else {
            // Add supplier ledger
            $addSupplierLedger = new SupplierLedger();
            $addSupplierLedger->supplier_id = $updatePurchase->supplier_id;
            $addSupplierLedger->purchase_id = $updatePurchase->id;
            $addSupplierLedger->row_type = 1;
            $addSupplierLedger->report_date = date('Y-m-d', strtotime($request->date));
            $addSupplierLedger->save();
        }


        // update product and variant Price & quantity
        if ($editType == 'purchased') {
            $loop = 0;
            foreach ($product_ids as $productId) {
                $variant_id = $variant_ids[$loop] != 'noid' ? $variant_ids[$loop] : NULL;
                if ($updatePurchase->is_last_created == 1) {
                    $this->purchaseUtil->updateProductAndVariantPrice($productId, $variant_id, $unit_costs_with_discount[$loop], $net_unit_costs[$loop], $profits[$loop], $selling_prices[$loop], $isEditProductPrice);
                }
                $loop++;
            }
        }

        if ($editType == 'purchased') {
            $this->purchaseUtil->updatePurchaseProduct($request, $isEditProductPrice, $updatePurchase->id);
        } else {
            $this->purchaseUtil->updatePurchaseOrderProduct($request, $isEditProductPrice, $updatePurchase->id);
        }

        // deleted not getting previous product
        $deletedUnusedPurchaseOrPoProducts = '';
        if ($editType == 'ordered') {
            $deletedUnusedPurchaseOrPoProducts = PurchaseOrderProduct::where('purchase_id', $updatePurchase->id)
                ->where('delete_in_update', 1)
                ->get();
        } else {
            $deletedUnusedPurchaseOrPoProducts = PurchaseProduct::where('purchase_id', $updatePurchase->id)
                ->where('delete_in_update', 1)
                ->get();
        }

        if (count($deletedUnusedPurchaseOrPoProducts) > 0) {
            foreach ($deletedUnusedPurchaseOrPoProducts as $deletedPurchaseProduct) {
                $storedProductId = $deletedPurchaseProduct->product_id;
                $storedVariantId = $deletedPurchaseProduct->product_variant_id;
                $deletedPurchaseProduct->delete();
                // Adjust deleted product stock
                $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);
                if (isset($request->warehouse_id)) {
                    $this->productStockUtil->adjustWarehouseStock($storedProductId, $storedVariantId, $request->warehouse_id);
                } else {
                    $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId, auth()->user()->branch_id);
                }
            }
        }

        if ($editType == 'purchased') {
            $purchase_products = DB::table('purchase_products')->where('purchase_id', $updatePurchase->id)->get();
            foreach ($purchase_products as $purchase_product) {
                $this->productStockUtil->adjustMainProductAndVariantStock($purchase_product->product_id, $purchase_product->product_variant_id);
                if (isset($request->warehouse_id)) {
                    $this->productStockUtil->addWarehouseProduct($purchase_product->product_id, $purchase_product->product_variant_id, $request->warehouse_id);
                    $this->productStockUtil->adjustWarehouseStock($purchase_product->product_id, $purchase_product->product_variant_id, $request->warehouse_id);
                } else {
                    $this->productStockUtil->addBranchProduct($purchase_product->product_id, $purchase_product->product_variant_id, auth()->user()->branch_id);
                    $this->productStockUtil->adjustBranchStock($purchase_product->product_id, $purchase_product->product_variant_id, auth()->user()->branch_id);
                }
            }

            if (isset($request->warehouse_id) && $request->warehouse_id != $storedWarehouseId) {
                foreach ($storePurchaseProducts as $PurchaseProduct) {
                    $this->productStockUtil->adjustWarehouseStock($PurchaseProduct->product_id, $PurchaseProduct->product_variant_id, $storedWarehouseId);
                }
            }
        }

        if ($editType == 'ordered') {
            $this->purchaseUtil->updatePoInvoiceQtyAndStatusPortion($updatePurchase);
        }

        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($updatePurchase);
        $this->supplierUtil->adjustSupplierForSalePaymentDue($updatePurchase->supplier_id); // Update

        session()->flash('successMsg', 'Successfully purchase is updated');
        return response()->json('Successfully purchase is updated');
    }

    // Product edit view
    public function edit($purchaseId, $editType)
    {
        $purchaseId = $purchaseId;
        $editType = $editType;
        $warehouses = DB::table('warehouses')->get();
        $purchase = DB::table('purchases')->where('id', $purchaseId)->select('id', 'warehouse_id', 'date')->first();
        return view('purchases.edit', compact('purchaseId', 'warehouses', 'purchase', 'editType'));
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
        $suppliers = Supplier::select('id',  'name',  'pay_term', 'pay_term_number', 'phone')->get();
        return response()->json($suppliers);
    }

    // Get all warehouse requested by ajax
    public function getAllUnit()
    {
        $unites = Unit::select('id', 'name')->get();
        return response()->json($unites);
    }

    // Get all warehouse requested by ajax
    public function getAllTax()
    {
        $taxes = DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();
        return response()->json($taxes);
    }

    // Search product by code
    public function searchProduct($product_code)
    {
        $product = Product::with(['product_variants', 'tax', 'unit'])
            ->where('type', 1)
            ->where('product_code', $product_code)
            ->where('status', 1)->first();

        if ($product) {
            return response()->json(['product' => $product]);
        } else {
            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')->where('variant_code', $product_code)->first();
            if ($variant_product) {
                return response()->json(['variant_product' => $variant_product]);
            }
        }

        return $this->nameSearchUtil->nameSearching($product_code);
    }

    // delete purchase method
    public function delete(Request $request, $purchaseId)
    {
        // get deleting purchase row
        $deletePurchase = purchase::with('supplier', 'purchase_products')->where('id', $purchaseId)->first();
        $supplier = DB::table('suppliers')->where('id', $deletePurchase->supplier_id)->first();
        //purchase payments
        $storedWarehouseId = $deletePurchase->warehouse_id;
        $storedBranchId = $deletePurchase->branch_id;
        $storedPayments = $deletePurchase->purchase_payments;
        $storePurchaseProducts = $deletePurchase->purchase_products;
        foreach ($deletePurchase->purchase_products as $purchase_product) {
            $SupplierProduct = SupplierProduct::where('supplier_id', $deletePurchase->supplier_id)
                ->where('product_id', $purchase_product->product_id)
                ->where('product_variant_id', $purchase_product->product_variant_id)
                ->first();
            if ($SupplierProduct) {
                $SupplierProduct->label_qty -= $purchase_product->quantity;;
                $SupplierProduct->save();
            }
        }

        $deletePurchase->delete();

        foreach ($storePurchaseProducts as $purchase_product) {
            $variant_id = $purchase_product->product_variant_id ? $purchase_product->product_variant_id : NULL;
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
                    $this->accountUtil->adjustAccountBalance($payment->account_id);
                }
            }
        }

        $this->supplierUtil->adjustSupplierForSalePaymentDue($supplier->id);
        return response()->json('Successfully purchase is deleted');
    }

    // Add product modal view with data
    public function addProductModalVeiw()
    {
        $units =  DB::table('units')->select('id', 'name', 'code_name')->get();
        $warranties = DB::table('warranties')->select('id', 'name', 'type')->get();
        $taxes = DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();
        $categories =  DB::table('categories')->where('parent_category_id', NULL)->orderBy('id', 'DESC')->get();
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
        $product = Product::with(['tax', 'unit'])
            ->where('id', $product_id)
            ->first();
        $units = DB::table('units')->select('id', 'name')->get();
        return view('purchases.ajax_view.recent_product_view', compact('product', 'units'));
    }

    // Get quick supplier modal
    public function addQuickSupplierModal()
    {
        return view('purchases.ajax_view.add_quick_supplier');
    }

    // Change purchase status
    public function addSupplier(Request $request)
    {
        return $this->util->storeQuickSupplier($request);
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
        $accounts = DB::table('accounts')->get();
        $purchase = Purchase::with(['supplier', 'branch', 'warehouse'])->where('id', $purchaseId)->first();
        return view('purchases.ajax_view.purchase_payment_modal', compact('purchase', 'accounts'));
    }

    public function paymentStore(Request $request, $purchaseId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_payment'];
        $purchase = Purchase::where('id', $purchaseId)->first();

        // Add purchase payment
        $addPurchasePayment = new PurchasePayment();
        $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'PPR') . date('my') . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');
        $addPurchasePayment->purchase_id = $purchase->id;
        $addPurchasePayment->is_advanced = $purchase->is_purchased == 0 ? 1 : 0;
        $addPurchasePayment->account_id = $request->account_id;
        $addPurchasePayment->pay_mode = $request->payment_method;
        $addPurchasePayment->paid_amount = $request->amount;
        $addPurchasePayment->date = $request->date;
        $addPurchasePayment->time = date('h:i:s a');
        $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
        $addPurchasePayment->month = date('F');
        $addPurchasePayment->year = date('Y');
        $addPurchasePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $addPurchasePayment->card_no = $request->card_no;
            $addPurchasePayment->card_holder = $request->card_holder_name;
            $addPurchasePayment->card_transaction_no = $request->card_transaction_no;
            $addPurchasePayment->card_type = $request->card_type;
            $addPurchasePayment->card_month = $request->month;
            $addPurchasePayment->card_year = $request->year;
            $addPurchasePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $addPurchasePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $addPurchasePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $addPurchasePayment->transaction_no = $request->transaction_no;
        }
        $addPurchasePayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {
            $purchasePaymentAttachment = $request->file('attachment');
            $purchasePaymentAttachmentName = uniqid() . '-' . '.' . $purchasePaymentAttachment->getClientOriginalExtension();
            $purchasePaymentAttachment->move(public_path('uploads/payment_attachment/'), $purchasePaymentAttachmentName);
            $addPurchasePayment->attachment = $purchasePaymentAttachmentName;
        }

        $addPurchasePayment->save();
        if ($request->account_id) {
            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->debit = $request->amount;
            //$addCashFlow->balance = $account->balance;
            $addCashFlow->purchase_payment_id = $addPurchasePayment->id;
            $addCashFlow->transaction_type = 3;
            $addCashFlow->cash_type = 1;
            $addCashFlow->date = $request->date;
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
            $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
            $addCashFlow->save();
        }

        $addSupplierLedger = new SupplierLedger();
        $addSupplierLedger->supplier_id = $purchase->supplier_id;
        $addSupplierLedger->purchase_payment_id = $addPurchasePayment->id;
        $addSupplierLedger->row_type = 2;
        $addSupplierLedger->report_date = date('Y-m-d', strtotime($request->date));
        $addSupplierLedger->save();

        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
        $this->supplierUtil->adjustSupplierForSalePaymentDue($purchase->supplier_id);

        return response()->json('Successfully payment is added.');
    }

    public function paymentEdit($paymentId)
    {
        $accounts = DB::table('accounts')->get();
        $payment = PurchasePayment::with(['purchase', 'purchase.branch', 'purchase.warehouse', 'purchase.supplier'])
            ->where('id', $paymentId)->first();
        return view('purchases.ajax_view.purchase_payment_edit_modal', compact('payment', 'accounts'));
    }

    public function paymentUpdate(Request $request, $paymentId)
    {
        $updatePurchasePayment = PurchasePayment::with(
            'account',
            'purchase.purchase_return',
            'cashFlow',
            'ledger'
        )->where('id', $paymentId)->first();

        $purchase = Purchase::where('id', $updatePurchasePayment->purchase_id)->first();

        // update purchase payment
        $updatePurchasePayment->account_id = $request->account_id;
        $updatePurchasePayment->pay_mode = $request->payment_method;
        $updatePurchasePayment->paid_amount = $request->amount;
        $updatePurchasePayment->date = $request->date;
        $updatePurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
        $updatePurchasePayment->month = date('F');
        $updatePurchasePayment->year = date('Y');
        $updatePurchasePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $updatePurchasePayment->card_no = $request->card_no;
            $updatePurchasePayment->card_holder = $request->card_holder_name;
            $updatePurchasePayment->card_transaction_no = $request->card_transaction_no;
            $updatePurchasePayment->card_type = $request->card_type;
            $updatePurchasePayment->card_month = $request->month;
            $updatePurchasePayment->card_year = $request->year;
            $updatePurchasePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $updatePurchasePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $updatePurchasePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $updatePurchasePayment->transaction_no = $request->transaction_no;
        }

        if ($request->hasFile('attachment')) {
            if ($updatePurchasePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $updatePurchasePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $updatePurchasePayment->attachment));
                }
            }
            $purchasePaymentAttachment = $request->file('attachment');
            $purchasePaymentAttachmentName = uniqid() . '-' . '.' . $purchasePaymentAttachment->getClientOriginalExtension();
            $purchasePaymentAttachment->move(public_path('uploads/payment_attachment/'), $purchasePaymentAttachmentName);
            $updatePurchasePayment->attachment = $purchasePaymentAttachmentName;
        }
        $updatePurchasePayment->save();
        $updatePurchasePayment->ledger->report_date = $updatePurchasePayment->report_date;
        $updatePurchasePayment->ledger->save();

        if ($request->account_id) {
            // Add or update cash flow
            $cashFlow = CashFlow::where('account_id', $request->account_id)
                ->where('purchase_payment_id', $updatePurchasePayment->id)->first();
            if ($cashFlow) {
                $cashFlow->debit = $request->amount;
                $cashFlow->date = $request->date;
                $cashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $cashFlow->month = date('F');
                $cashFlow->year = date('Y');
                $cashFlow->admin_id = auth()->user()->id;
                $cashFlow->save();
                $cashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
                $cashFlow->save();
            } else {
                if ($updatePurchasePayment->cashFlow) {
                    $storeAccountId = $updatePurchasePayment->cashFlow->account_id;
                    $updatePurchasePayment->cashFlow->delete();
                    $this->accountUtil->adjustAccountBalance($storeAccountId);
                }

                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->debit = $request->amount;
                $addCashFlow->purchase_payment_id = $updatePurchasePayment->id;
                $addCashFlow->transaction_type = 3;
                $addCashFlow->cash_type = 1;
                $addCashFlow->date = $request->date;
                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
                $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
                $addCashFlow->save();
            }
        } else {
            if ($updatePurchasePayment->cashFlow) {
                $storeAccountId = $updatePurchasePayment->cashFlow->account_id;
                $updatePurchasePayment->cashFlow->delete();
                $this->accountUtil->adjustAccountBalance($storeAccountId);
            }
        }

        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
        $this->supplierUtil->adjustSupplierForSalePaymentDue($purchase->supplier_id);

        return response()->json('Successfully payment is updated.');
    }

    public function returnPaymentModal($purchaseId)
    {
        $accounts = DB::table('accounts')->get();
        $purchase = Purchase::with(['supplier', 'branch', 'warehouse'])->where('id', $purchaseId)->first();
        return view('purchases.ajax_view.purchase_return_payment', compact('purchase', 'accounts'));
    }

    public function returnPaymentStore(Request $request, $purchaseId)
    {
        $purchase = Purchase::with(['purchase_return'])->where('id', $purchaseId)->first();
        // update purchase return
        if ($purchase->purchase_return) {
            $purchase->purchase_return->total_return_due_received += (float)$request->amount;
            $purchase->purchase_return->total_return_due -= (float)$request->amount;
            $purchase->purchase_return->save();
        }

        // Add purchase payment
        $addPurchasePayment = new PurchasePayment();
        $addPurchasePayment->invoice_id = 'PRP' . date('my') . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');
        $addPurchasePayment->purchase_id = $purchase->id;
        $addPurchasePayment->account_id = $request->account_id;
        $addPurchasePayment->pay_mode = $request->payment_method;
        $addPurchasePayment->paid_amount = $request->amount;
        $addPurchasePayment->payment_type = 2;
        $addPurchasePayment->date = $request->date;
        $addPurchasePayment->time = date('h:i:s a');
        $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
        $addPurchasePayment->month = date('F');
        $addPurchasePayment->year = date('Y');
        $addPurchasePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $addPurchasePayment->card_no = $request->card_no;
            $addPurchasePayment->card_holder = $request->card_holder_name;
            $addPurchasePayment->card_transaction_no = $request->card_transaction_no;
            $addPurchasePayment->card_type = $request->card_type;
            $addPurchasePayment->card_month = $request->month;
            $addPurchasePayment->card_year = $request->year;
            $addPurchasePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $addPurchasePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $addPurchasePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $addPurchasePayment->transaction_no = $request->transaction_no;
        }
        $addPurchasePayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {
            $purchasePaymentAttachment = $request->file('attachment');
            $purchasePaymentAttachmentName = uniqid() . '-' . '.' . $purchasePaymentAttachment->getClientOriginalExtension();
            $purchasePaymentAttachment->move(public_path('uploads/payment_attachment/'), $purchasePaymentAttachmentName);
            $addPurchasePayment->attachment = $purchasePaymentAttachmentName;
        }

        $addPurchasePayment->save();

        if ($request->account_id) {
            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->credit = $request->amount;
            $addCashFlow->purchase_payment_id = $addPurchasePayment->id;
            $addCashFlow->transaction_type = 3;
            $addCashFlow->cash_type = 2;
            $addCashFlow->date = $request->date;
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
            $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
            $addCashFlow->save();
        }

        $addSupplierLedger = new SupplierLedger();
        $addSupplierLedger->supplier_id = $purchase->supplier_id;
        $addSupplierLedger->purchase_payment_id = $addPurchasePayment->id;
        $addSupplierLedger->row_type = 2;
        $addSupplierLedger->report_date = date('Y-m-d', strtotime($request->date));
        $addSupplierLedger->save();

        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
        $this->supplierUtil->adjustSupplierForSalePaymentDue($purchase->supplier_id);
        return response()->json('Successfully payment is added.');
    }

    public function returnPaymentEdit($paymentId)
    {
        $accounts = DB::table('accounts')->get();
        $payment = PurchasePayment::with(['purchase', 'purchase.branch', 'purchase.warehouse', 'purchase.supplier'])
            ->where('id', $paymentId)->first();
        return view('purchases.ajax_view.purchase_return_payment_edit', compact('payment', 'accounts'));
    }

    public function returnPaymentUpdate(Request $request, $paymentId)
    {
        $updatePurchasePayment = PurchasePayment::with('account', 'purchase.purchase_return', 'cashFlow')->where('id', $paymentId)->first();
        $purchase = Purchase::where('id', $updatePurchasePayment->purchase_id)->first();
        // Update purchase return
        $purchaseReturn = PurchaseReturn::where('id', $updatePurchasePayment->purchase->purchase_return->id)->first();
        $purchaseReturn->total_return_due_received -= $updatePurchasePayment->paid_amount;
        $purchaseReturn->total_return_due_received += (float)$request->amount;
        $purchaseReturn->total_return_due += $updatePurchasePayment->paid_amount;
        $purchaseReturn->total_return_due -= (float)$request->amount;
        $purchaseReturn->save();

        // update purchase payment
        $updatePurchasePayment->account_id = $request->account_id;
        $updatePurchasePayment->pay_mode = $request->payment_method;
        $updatePurchasePayment->paid_amount = $request->amount;
        $updatePurchasePayment->date = $request->date;
        $updatePurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
        $updatePurchasePayment->month = date('F');
        $updatePurchasePayment->year = date('Y');
        $updatePurchasePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $updatePurchasePayment->card_no = $request->card_no;
            $updatePurchasePayment->card_holder = $request->card_holder_name;
            $updatePurchasePayment->card_transaction_no = $request->card_transaction_no;
            $updatePurchasePayment->card_type = $request->card_type;
            $updatePurchasePayment->card_month = $request->month;
            $updatePurchasePayment->card_year = $request->year;
            $updatePurchasePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $updatePurchasePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $updatePurchasePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $updatePurchasePayment->transaction_no = $request->transaction_no;
        }

        if ($request->hasFile('attachment')) {
            if ($updatePurchasePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $updatePurchasePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $updatePurchasePayment->attachment));
                }
            }
            $purchasePaymentAttachment = $request->file('attachment');
            $purchasePaymentAttachmentName = uniqid() . '-' . '.' . $purchasePaymentAttachment->getClientOriginalExtension();
            $purchasePaymentAttachment->move(public_path('uploads/payment_attachment/'), $purchasePaymentAttachmentName);
            $updatePurchasePayment->attachment = $purchasePaymentAttachmentName;
        }
        $updatePurchasePayment->save();
        $updatePurchasePayment->ledger->report_date = $updatePurchasePayment->report_date;
        $updatePurchasePayment->ledger->save();

        if ($request->account_id) {
            // Add or update cash flow
            $cashFlow = CashFlow::where('account_id', $request->account_id)
                ->where('purchase_payment_id', $updatePurchasePayment->id)
                ->first();
            if ($cashFlow) {
                $cashFlow->credit = $request->amount;
                $cashFlow->date = $request->date;
                $cashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $cashFlow->month = date('F');
                $cashFlow->year = date('Y');
                $cashFlow->admin_id = auth()->user()->id;
                $cashFlow->save();
                $cashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
                $cashFlow->save();
            } else {
                if ($updatePurchasePayment->cashFlow) {
                    $storeAccountId = $updatePurchasePayment->cashFlow->account_id;
                    $updatePurchasePayment->cashFlow->delete();
                    $this->accountUtil->adjustAccountBalance($storeAccountId);
                }

                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->credit = $request->amount;
                $addCashFlow->purchase_payment_id = $updatePurchasePayment->id;
                $addCashFlow->transaction_type = 3;
                $addCashFlow->cash_type = 1;
                $addCashFlow->date = $request->date;
                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
                $this->accountUtil->adjustAccountBalance($request->account_id);
                $addCashFlow->save();
            }
        } else {
            if ($updatePurchasePayment->cashFlow) {
                $storeAccountId = $updatePurchasePayment->cashFlow->account_id;
                $updatePurchasePayment->cashFlow->delete();
                $this->accountUtil->adjustAccountBalance($storeAccountId);
            }
        }

        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
        $this->supplierUtil->adjustSupplierForSalePaymentDue($purchase->supplier_id);

        return response()->json('Successfully payment is updated FF.');
    }

    //Get purchase wise payment list
    public function paymentList($purchaseId)
    {
        $purchase = Purchase::with(['supplier', 'purchase_payments', 'purchase_payments.account'])
            ->where('id', $purchaseId)
            ->first();
        return view('purchases.ajax_view.view_payment_list', compact('purchase'));
    }

    public function paymentDetails($paymentId)
    {
        $payment = PurchasePayment::with('purchase', 'purchase.branch', 'purchase.warehouse', 'purchase.supplier')->where('id', $paymentId)->first();
        return view('purchases.ajax_view.payment_details', compact('payment'));
    }

    // Delete purchase payment
    public function paymentDelete(Request $request, $paymentId)
    {
        $deletePurchasePayment = PurchasePayment::with('account', 'purchase', 'cashFlow')
            ->where('id', $paymentId)
            ->first();

        if (!is_null($deletePurchasePayment)) {
            // Update previous account and delete previous cashflow.
            if ($deletePurchasePayment->cashFlow) {
                $storeAccountId = $deletePurchasePayment->cashFlow->account_id;
                $deletePurchasePayment->cashFlow->delete();
                $this->accountUtil->adjustAccountBalance($storeAccountId);
            }

            if ($deletePurchasePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $deletePurchasePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $deletePurchasePayment->attachment));
                }
            }
            //Update Supplier due 
            if ($deletePurchasePayment->payment_type == 1) {
                $supplier = Supplier::where('id', $deletePurchasePayment->purchase->supplier_id)->first();
                $storedPurchaseId = $deletePurchasePayment->purchase_id;
                $deletePurchasePayment->delete();
                if ($storedPurchaseId) {
                    $purchase = Purchase::where('id', $storedPurchaseId)->first();
                    $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
                }
                $this->supplierUtil->adjustSupplierForSalePaymentDue($supplier->id);
            } else {
                if ($deletePurchasePayment->purchase) {
                    $storedPurchase = $deletePurchasePayment->purchase;
                    $purchaseReturn = PurchaseReturn::where('id', $deletePurchasePayment->purchase->purchase_return->id)->first();
                    $purchaseReturn->total_return_due_received -= $deletePurchasePayment->paid_amount;
                    $purchaseReturn->total_return_due += $deletePurchasePayment->paid_amount;
                    $purchaseReturn->save();
                    $deletePurchasePayment->delete();
                    $this->purchaseUtil->adjustPurchaseInvoiceAmounts($storedPurchase);
                    $this->supplierUtil->adjustSupplierForSalePaymentDue($storedPurchase->supplier_id);
                } else {
                    $purchaseReturn = PurchaseReturn::where('id', $deletePurchasePayment->supplier_return->id)->first();
                    $purchaseReturn->total_return_due_received -= $deletePurchasePayment->paid_amount;
                    $purchaseReturn->total_return_due += $deletePurchasePayment->paid_amount;
                    $purchaseReturn->save();
                    $deletePurchasePayment->delete();
                    $this->supplierUtil->adjustSupplierForSalePaymentDue($purchaseReturn->supplier_id);
                }
            }
        }

        return response()->json('Successfully payment is deleted.');
    }

    //Show Change status modal
    public function changeStatusModal($purchaseId)
    {
        $purchase = DB::table('purchases')->select('id', 'purchase_status')->where('id', $purchaseId)->first();
        return view('purchases.ajax_view.change_status_modal', compact('purchase'));
    }
}
