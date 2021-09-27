<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Product;
use App\Models\CashFlow;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Utils\PurchaseUtil;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Utils\NameSearchUtil;
use App\Models\ProductVariant;
use App\Models\SupplierLedger;
use App\Models\PurchasePayment;
use App\Models\PurchaseProduct;
use App\Models\SupplierProduct;
use App\Models\ProductWarehouse;
use DB;
use App\Models\ProductBranchVariant;
use App\Models\ProductWarehouseVariant;
use App\Models\PurchaseReturn;
use App\Utils\AccountUtil;
use App\Utils\ProductStockUtil;
use App\Utils\SupplierUtil;
use App\Utils\Util;

class PurchaseController extends Controller
{
    protected $purchaseUtil;
    protected $nameSearchUtil;
    protected $util;
    protected $supplierUtil;
    protected $productStockUtil;
    protected $accountUtil;
    public function __construct(
        NameSearchUtil $nameSearchUtil,
        PurchaseUtil $purchaseUtil,
        Util $util,
        SupplierUtil $supplierUtil,
        ProductStockUtil $productStockUtil,
        AccountUtil $accountUtil
    ) {
        $this->nameSearchUtil = $nameSearchUtil;
        $this->purchaseUtil = $purchaseUtil;
        $this->util = $util;
        $this->supplierUtil = $supplierUtil;
        $this->productStockUtil = $productStockUtil;
        $this->accountUtil = $accountUtil;
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
        return view('purchases.index_v2', compact('branches'));
    }

    public function purchaseProductList(Request $request)
    {
        if ($request->ajax()) {
            return $this->purchaseUtil->purchaseProductListTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $suppliers = DB::table('suppliers')->get(['id', 'name', 'phone']);
        $categories = DB::table('categories')->where('parent_category_id', NULL)->get(['id', 'name']);
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
        }

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $quantities = $request->quantities;
        $unit_names = $request->unit_names;
        $discounts = $request->unit_discounts;
        $unit_costs = $request->unit_costs;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $unit_costs_with_discount = $request->unit_costs_with_discount;
        $subtotal = $request->subtotals;
        $tax_percents = $request->tax_percents;
        $unit_taxes = $request->unit_taxes;
        $net_unit_costs = $request->net_unit_costs;
        $linetotals = $request->linetotals;
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

        // generate invoice ID
        $invoiceId = 1;
        $lastPurchase = DB::table('purchases')->orderBy('id', 'desc')->first();
        if ($lastPurchase) {
            $invoiceId = ++$lastPurchase->id;
        }

        $getLastCreated = Purchase::where('is_last_created', 1)->first();
        if ($getLastCreated) {
            $getLastCreated->is_last_created = 0;
            $getLastCreated->save();
        }

        // add purchase total information
        $addPurchase = new Purchase();
        $addPurchase->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . date('my') . $invoiceId;
        $addPurchase->warehouse_id = isset($request->warehouse_id) ? $request->warehouse_id : NULL;
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
        $addPurchase->date = $request->date;
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

        // add purchase product
        $index = 0;
        foreach ($product_ids as $productId) {
            $addPurchaseProduct = new PurchaseProduct();
            $addPurchaseProduct->purchase_id = $addPurchase->id;
            $addPurchaseProduct->product_id = $productId;
            $addPurchaseProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $addPurchaseProduct->quantity = $quantities[$index];
            $addPurchaseProduct->unit = $unit_names[$index];
            $addPurchaseProduct->unit_cost = $unit_costs[$index];
            $addPurchaseProduct->unit_discount = $discounts[$index];
            $addPurchaseProduct->unit_cost_with_discount = $unit_costs_with_discount[$index];
            $addPurchaseProduct->subtotal = $subtotal[$index];
            $addPurchaseProduct->unit_tax_percent = $tax_percents[$index];
            $addPurchaseProduct->unit_tax = $unit_taxes[$index];
            $addPurchaseProduct->net_unit_cost = $net_unit_costs[$index];
            $addPurchaseProduct->line_total = $linetotals[$index];

            if ($isEditProductPrice == '1') {
                $addPurchaseProduct->profit_margin = $profits[$index];
                $addPurchaseProduct->selling_price = $selling_prices[$index];
            }

            if (isset($request->lot_number)) {
                $addPurchaseProduct->lot_no = $request->lot_number[$index];
            }

            $addPurchaseProduct->save();
            $index++;
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
            $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'PPI') . date('ymd') . $invoiceId;
            $addPurchasePayment->purchase_id = $addPurchase->id;
            $addPurchasePayment->account_id = $request->account_id;
            $addPurchasePayment->pay_mode = $request->payment_method;
            $addPurchasePayment->paid_amount = $request->paying_amount;
            $addPurchasePayment->date = $request->date;
            $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
            $addPurchasePayment->month = date('F');
            $addPurchasePayment->year = date('Y');
            $addPurchasePayment->note = $request->payment_note;

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

            // Add supplier ledger
            $addSupplierLedger = new SupplierLedger();
            $addSupplierLedger->supplier_id = $request->supplier_id;
            $addSupplierLedger->purchase_payment_id = $addPurchasePayment->id;
            $addSupplierLedger->row_type = 2;
            $addSupplierLedger->report_date = date('Y-m-d', strtotime($request->date));
            $addSupplierLedger->save();
        }

        // update main product and variant price & Stock
        $productIndex = 0;
        foreach ($product_ids as $productId) {
            $updateProductQty = Product::where('id', $productId)->first();
            $updateProductQty->is_purchased = 1;
            if ($updateProductQty->is_variant == 0) {
                $updateProductQty->product_cost = $unit_costs[$productIndex];
                $updateProductQty->product_cost_with_tax = $unit_costs_inc_tax[$productIndex];
                if ($isEditProductPrice == '1') {
                    $updateProductQty->profit = $profits[$productIndex];
                    $updateProductQty->product_price = $selling_prices[$productIndex];
                }
            }
            $updateProductQty->save();

            if ($variant_ids[$productIndex] != 'noid') {
                $updateVariantQty = ProductVariant::where('id', $variant_ids[$productIndex])->where('product_id', $productId)->first();
                $updateVariantQty->variant_cost = $unit_costs[$productIndex];
                $updateVariantQty->variant_cost_with_tax = $unit_costs_inc_tax[$productIndex];
                if ($isEditProductPrice == '1') {
                    $updateVariantQty->variant_profit = $profits[$productIndex];
                    $updateVariantQty->variant_price = $selling_prices[$productIndex];
                }

                $updateVariantQty->is_purchased = 1;
                $updateVariantQty->save();
            }
            $productIndex++;
        }


        $__index = 0;
        foreach ($product_ids as $productId) {
            $variant_id = $variant_ids[$__index] != 'noid' ? $variant_ids[$__index] : NULL;
            $this->productStockUtil->adjustMainProductAndVariantStock($productId, $variant_id);
            if (isset($request->warehouse_id)) {
                $this->productStockUtil->adjustWarehouseStock($productId, $variant_id, $request->warehouse_id);
            } else if (auth()->user()->branch_id) {
                $this->productStockUtil->adjustBranchStock($productId, $variant_id, auth()->user()->branch_id);
            } else {
                $this->productStockUtil->adjustMainBranchStock($productId, $variant_id);
            }
            $__index++;
        }
        $this->supplierUtil->adjustSupplierForSalePaymentDue($request->supplier_id);

        session()->flash('successMsg', 'Successfully purchase is added');
        return response()->json('Successfully purchase is added');
    }

    // update purchase method
    public function update(Request $request)
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
        $unit_names = $request->unit_names;
        $discounts = $request->unit_discounts;
        $unit_costs = $request->unit_costs;
        $unit_costs_with_discount = $request->unit_costs_with_discount;
        $subtotal = $request->subtotals;
        $tax_percents = $request->tax_percents;
        $unit_taxes = $request->unit_taxes;
        $net_unit_costs = $request->net_unit_costs;
        $linetotals = $request->linetotals;
        $profits = $request->profits;
        $selling_prices = $request->selling_prices;

        // get updatable purchase row
        $updatePurchase = purchase::with(['purchase_products', 'ledger'])->where('id', $request->id)->first();
        $storedWarehouseId = $updatePurchase->warehouse_id;

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

        foreach ($updatePurchase->purchase_products as $purchase_product) {
            $purchase_product->delete_in_update = 1;
            $purchase_product->save();
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
        // generate invoice ID
        $invoiceId = 1;
        $lastPurchase = DB::table('purchases')->orderBy('id', 'desc')->first();
        if ($lastPurchase) {
            $invoiceId = ++$lastPurchase->id;
        }
     
        // update purchase total information
        $updatePurchase->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . date('my') . $invoiceId;
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
        $updatePurchase->ledger->report_date = $updatePurchase->report_date;
        $updatePurchase->ledger->save();

        // update product and variant Price & quantity
        $storePurchaseProducts = $updatePurchase->purchase_products;
        $productIndex = 0;
        foreach ($product_ids as $productId) {
            if ($updatePurchase->is_last_created == 1) {
                $updateProduct = Product::where('id', $productId)->first();
                if ($updateProduct->is_variant == 0) {
                    $updateProduct->product_cost = $unit_costs_with_discount[$productIndex];
                    $updateProduct->product_cost_with_tax = $net_unit_costs[$productIndex];
                    if ($isEditProductPrice == '1') {
                        $updateProduct->profit = $profits[$productIndex];
                        $updateProduct->product_price = $selling_prices[$productIndex];
                    }
                }
                $updateProduct->save();
            }

            if ($updatePurchase->is_last_created == 1) {
                if ($variant_ids[$productIndex] != 'noid') {
                    $updateVariant = ProductVariant::where('id', $variant_ids[$productIndex])
                        ->where('product_id', $productId)
                        ->first();
                    $updateVariant->variant_cost = $unit_costs_with_discount[$productIndex];
                    $updateVariant->variant_cost_with_tax = $net_unit_costs[$productIndex];
                    if ($isEditProductPrice == '1') {
                        $updateVariant->variant_profit = $profits[$productIndex];
                        $updateVariant->variant_price = $selling_prices[$productIndex];
                    }
                    $updateVariant->save();
                }
            }
            $productIndex++;
        }

        // add purchase product
        $index = 0;
        foreach ($product_ids as $productId) {
            $filterVariantId = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $updatePurchaseProduct = PurchaseProduct::where('purchase_id', $updatePurchase->id)->where('product_id', $productId)->where('product_variant_id', $filterVariantId)->first();
            if ($updatePurchaseProduct) {
                $updatePurchaseProduct->product_id = $productId;
                $updatePurchaseProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $updatePurchaseProduct->quantity = $quantities[$index];
                $updatePurchaseProduct->unit = $unit_names[$index];
                $updatePurchaseProduct->unit_cost = $unit_costs[$index];
                $updatePurchaseProduct->unit_discount = $discounts[$index];
                $updatePurchaseProduct->unit_cost_with_discount = $unit_costs_with_discount[$index];
                $updatePurchaseProduct->subtotal = $subtotal[$index];
                $updatePurchaseProduct->unit_tax_percent = $tax_percents[$index];
                $updatePurchaseProduct->unit_tax = $unit_taxes[$index];
                $updatePurchaseProduct->net_unit_cost = $net_unit_costs[$index];
                $updatePurchaseProduct->line_total = $linetotals[$index];

                if ($isEditProductPrice == '1') {
                    $updatePurchaseProduct->profit_margin = $profits[$index];
                    $updatePurchaseProduct->selling_price = $selling_prices[$index];
                }

                if (isset($request->lot_number)) {
                    $updatePurchaseProduct->lot_no = $request->lot_number[$index];
                }
                $updatePurchaseProduct->delete_in_update = 0;
                $updatePurchaseProduct->save();
            } else {
                $addPurchaseProduct = new PurchaseProduct();
                $addPurchaseProduct->purchase_id = $updatePurchase->id;
                $addPurchaseProduct->product_id = $productId;
                $addPurchaseProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $addPurchaseProduct->quantity = $quantities[$index];
                $addPurchaseProduct->unit = $unit_names[$index];
                $addPurchaseProduct->unit_cost = $unit_costs[$index];
                $addPurchaseProduct->unit_discount = $discounts[$index];
                $addPurchaseProduct->unit_cost_with_discount = $unit_costs_with_discount[$index];
                $addPurchaseProduct->subtotal = $subtotal[$index];
                $addPurchaseProduct->unit_tax_percent = $tax_percents[$index];
                $addPurchaseProduct->unit_tax = $unit_taxes[$index];
                $addPurchaseProduct->net_unit_cost = $net_unit_costs[$index];
                $addPurchaseProduct->line_total = $linetotals[$index];

                if ($isEditProductPrice == '1') {
                    $addPurchaseProduct->profit_margin = $profits[$index];
                    $addPurchaseProduct->selling_price = $selling_prices[$index];
                }

                if (isset($request->lot_number)) {
                    $addPurchaseProduct->lot_no = $request->lot_number[$index];
                }
                $addPurchaseProduct->save();
            }
            $index++;
        }

        // deleted not getting previous product
        $deletedPurchaseProducts = PurchaseProduct::where('purchase_id', $request->id)->where('delete_in_update', 1)->get();
        if (count($deletedPurchaseProducts) > 0) {
            foreach ($deletedPurchaseProducts as $deletedPurchaseProduct) {
                $storedProductId = $deletedPurchaseProduct->product_id;
                $storedVariantId = $deletedPurchaseProduct->product_variant_id;
                $deletedPurchaseProduct->delete();
                // Adjust deleted product stock
                $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);
                if (isset($request->warehouse_id)) {
                    $this->productStockUtil->adjustWarehouseStock($storedProductId, $storedVariantId, $request->warehouse_id);
                } else if (auth()->user()->branch_id) {
                    $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId, auth()->user()->branch_id);
                } else {
                    $this->productStockUtil->adjustMainBranchStock($storedProductId, $storedVariantId);
                }
            }
        }

        $purchase_products = DB::table('purchase_products')->where('purchase_id', $updatePurchase->id)->get();
        foreach ($purchase_products as $purchase_product) {
            $this->productStockUtil->adjustMainProductAndVariantStock($purchase_product->product_id, $purchase_product->product_variant_id);
            if (isset($request->warehouse_id)) {
                $this->productStockUtil->adjustWarehouseStock($purchase_product->product_id, $purchase_product->product_variant_id, $request->warehouse_id);
            } else if (auth()->user()->branch_id) {
                $this->productStockUtil->adjustBranchStock($purchase_product->product_id, $purchase_product->product_variant_id, auth()->user()->branch_id);
            } else {
                $this->productStockUtil->adjustMainBranchStock($purchase_product->product_id, $purchase_product->product_variant_id);
            }
        }

        if (isset($request->warehouse_id) && $request->warehouse_id != $storedWarehouseId) {
            foreach ($storePurchaseProducts as $PurchaseProduct) {
                $this->productStockUtil->adjustWarehouseStock($PurchaseProduct->product_id, $PurchaseProduct->product_variant_id, $storedWarehouseId);
            }
        }

        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($updatePurchase);
        $this->supplierUtil->adjustSupplierForSalePaymentDue($updatePurchase->supplier_id);

        session()->flash('successMsg', 'Successfully purchase is updated');
        return response()->json('Successfully purchase is updated');
    }

    // Product edit view
    public function edit($purchaseId)
    {
        $purchaseId = $purchaseId;
        $warehouses = DB::table('warehouses')->get();
        $purchase = DB::table('purchases')->where('id', $purchaseId)->select('id', 'warehouse_id', 'date')->first();
        return view('purchases.edit', compact('purchaseId', 'warehouses', 'purchase'));
    }

    // Get editable purchase
    public function editablePurchase($purchaseId)
    {
        $purchase = Purchase::with([
            'warehouse',
            'supplier',
            'purchase_products',
            'purchase_products.product',
            'purchase_products.variant'
        ])->where('id', $purchaseId)->first();
        return response()->json($purchase);
    }

    public function editPurchasedProduct($purchaseId, $productId, $variantId)
    {
        $variantId = $variantId != 'NULL' ? $variantId : NULL;
        $purchase = DB::table('purchases')->where('purchases.id', $purchaseId)
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->select(
                'purchases.*',
                'suppliers.name as s_name',
                'warehouses.warehouse_name as w_name',
                'warehouses.warehouse_code as w_code',
            )->first();

        $purchaseProduct = PurchaseProduct::with('product:id,name,product_code', 'variant:id,variant_name,variant_code')
            ->where('purchase_id', $purchaseId)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)->first();

        return view('purchases.edit_purchased_product', compact('purchase', 'purchaseProduct'));
    }

    public function PurchasedProductUpdate(Request $request, $purchaseId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'purchase'])->first();
        $isEditProductPrice = json_decode($prefixSettings->purchase, true)['is_edit_pro_price'];
        $variantId = $request->variant_id != 'noid' ? $request->variant_id : NULL;
        $updatePurchaseProduct = PurchaseProduct::where('purchase_id', $purchaseId)
            ->where('product_id', $request->product_id)
            ->where('product_variant_id', $variantId)->first();

        $updatePurchase = Purchase::where('id', $purchaseId)->first();
        $updatePurchase->date = date('d-m-Y', strtotime($request->date));
        $updatePurchase->report_date = date('Y-m-d', strtotime($request->date));
        $updatePurchase->total_purchase_amount -= $updatePurchaseProduct->line_total;
        $updatePurchase->total_purchase_amount += $request->linetotal;

        $SupplierProduct = SupplierProduct::where('supplier_id', $updatePurchase->supplier_id)
            ->where('product_id', $updatePurchaseProduct->product_id)
            ->where('product_variant_id', $updatePurchaseProduct->product_variant_id)
            ->first();

        if ($SupplierProduct) {
            $SupplierProduct->label_qty -= (float)$updatePurchaseProduct->quantity;
            $SupplierProduct->label_qty += (float)$request->quantity;
            $SupplierProduct->save();
        }
        // update product and variant quantity for adjustment End

        // update product and variant quantity
        if ($updatePurchase->is_last_created == 1) {
            $updateProductQty = Product::where('id', $request->product_id)->first();
            if ($updateProductQty->is_variant == 0) {
                $updateProductQty->product_cost = $request->unit_cost_with_discount;
                $updateProductQty->product_cost_with_tax = $request->net_unit_cost;
                if ($isEditProductPrice == '1') {
                    $updateProductQty->profit = $request->profit;
                    $updateProductQty->product_price = $request->selling_price;
                }
            }
            $updateProductQty->save();

            if ($variantId != NULL) {
                $updateVariantQty = ProductVariant::where('id', $variantId)->where('product_id', $request->product_id)->first();
                $updateVariantQty->variant_cost = $request->unit_cost_with_discount;
                $updateVariantQty->variant_cost_with_tax = $request->net_unit_cost;
                if ($isEditProductPrice == '1') {
                    $updateVariantQty->variant_profit = $request->profit;
                    $updateProductQty->variant_price = $request->selling_price;
                }

                $updateVariantQty->save();
            }
        }

        $updatePurchaseProduct->product_id = $request->product_id;
        $updatePurchaseProduct->product_variant_id = $variantId;
        $updatePurchaseProduct->quantity = $request->quantity;
        $updatePurchaseProduct->unit_cost = $request->unit_cost;
        $updatePurchaseProduct->unit_discount = $request->unit_discount;
        $updatePurchaseProduct->unit_cost_with_discount = $request->unit_cost_with_discount;
        $updatePurchaseProduct->subtotal = $request->subtotal;
        $updatePurchaseProduct->unit_tax_percent = $request->tax_percent;
        $updatePurchaseProduct->unit_tax = $request->unit_tax;
        $updatePurchaseProduct->net_unit_cost = $request->net_unit_cost;
        $updatePurchaseProduct->line_total = $request->linetotal;

        if ($isEditProductPrice == '1') {
            $updatePurchaseProduct->profit_margin = $request->profit;
            $updatePurchaseProduct->selling_price = $request->selling_price;
        }

        if (isset($request->lot_number)) {
            $updatePurchaseProduct->lot_no = $request->lot_number;
        }
        $updatePurchaseProduct->save();

        // update Business location or Warehouse product and variant quantity for adjustment
        if ($updatePurchase->warehouse_id) {
            $this->productStockUtil->adjustWarehouseStock($request->product_id, $variantId, $updatePurchase->warehouse_id);
        } elseif ($updatePurchase->branch_id) {
            $this->productStockUtil->adjustBranchStock($request->product_id, $variantId, $updatePurchase->branch_id);
        } else {
            $this->productStockUtil->adjustMainBranchStock($request->product_id, $variantId);
        }

        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($updatePurchase);
        $this->productStockUtil->adjustMainProductAndVariantStock($request->product_id, $variantId);
        $this->supplierUtil->adjustSupplierForSalePaymentDue($updatePurchase->supplier_id);

        session()->flash('successMsg', 'Successfully purchased Product is updated');
        return response()->json('Successfully purchased Product is updated');
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
            } elseif($storedBranchId){
                $this->productStockUtil->adjustBranchStock($purchase_product->product_id, $variant_id, $storedBranchId);
            }else {
                $this->productStockUtil->adjustMainBranchStock($purchase_product->product_id, $variant_id);
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
        $addProduct = new Product();
        $tax_id = NULL;
        if ($request->tax_id) {
            $tax_id = explode('-', $request->tax_id)[0];
        }

        $this->validate(
            $request,
            [
                'name' => 'required',
                'product_code' => 'required',
                'unit_id' => 'required',
                'product_price' => 'required',
                'product_cost' => 'required',
                'product_cost_with_tax' => 'required',
            ],
            [
                'unit_id.required' => 'Product unit field is required.',
            ]
        );

        $addProduct->type = 1;
        $addProduct->name = $request->name;
        $addProduct->product_code = $request->product_code;
        $addProduct->category_id = $request->category_id;
        $addProduct->parent_category_id = $request->child_category_id;
        $addProduct->brand_id = $request->brand_id;
        $addProduct->unit_id = $request->unit_id;
        $addProduct->product_cost = $request->product_cost;
        $addProduct->profit = $request->profit ? $request->profit : 0.00;
        $addProduct->product_cost_with_tax = $request->product_cost_with_tax;
        $addProduct->product_price = $request->product_price;
        $addProduct->alert_quantity = $request->alert_quantity;
        $addProduct->tax_id = $tax_id;
        $addProduct->product_details = $request->product_details;
        $addProduct->is_purchased = 1;
        $addProduct->barcode_type = $request->barcode_type;
        $addProduct->warranty_id = $request->warranty_id;
        $addProduct->is_show_in_ecom = isset($request->is_show_in_ecom) ? 1 : 0;
        $addProduct->is_show_emi_on_pos = isset($request->is_show_emi_on_pos) ? 1 : 0;
        $addProduct->save();
        return response()->json($addProduct);
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
        // generate invoice ID
        $i = 5;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        // Add purchase payment
        $addPurchasePayment = new PurchasePayment();
        $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'PPR') . date('ymd') . $invoiceId;
        $addPurchasePayment->purchase_id = $purchase->id;
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

        // generate invoice ID
        $i = 5;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        // Add purchase payment
        $addPurchasePayment = new PurchasePayment();
        $addPurchasePayment->invoice_id = 'PRP' . date('dmy') . $invoiceId;
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
