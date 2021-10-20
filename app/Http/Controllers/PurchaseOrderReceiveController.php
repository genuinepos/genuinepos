<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\SupplierLedger;
use App\Models\PurchasePayment;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderProduct;
use App\Utils\AccountUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\ProductStockUtil;
use App\Utils\SupplierUtil;

class PurchaseOrderReceiveController extends Controller
{
    protected $accountUtil;
    protected $invoiceVoucherRefIdUtil;
    protected $supplierUtil;
    protected $productStockUtil;

    public function __construct(
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        SupplierUtil $supplierUtil,
        ProductStockUtil $productStockUtil
    ) {
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->supplierUtil = $supplierUtil;
        $this->ProductStockUtil = $productStockUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function processReceive($purchaseId)
    {
        $purchase = Purchase::with([
            'supplier:id,name,phone',
            'purchase_order_products',
            'purchase_order_products.product',
            'purchase_order_products.variant',
        ])->where('id', $purchaseId)->first();
        $warehouses = DB::table('warehouses')->where('branch_id', auth()->user()->branch_id)->get();
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number')->get();
        return view('purchases.order_receive.process_to_receive', compact('purchase', 'warehouses', 'accounts'));
    }

    public function processReceiveStore(Request $request, $purchaseId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_invoice'];
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_payment'];

        $purchase = Purchase::with('purchase_order_products')->where('id', $purchaseId)->first();
        $purchase->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . date('my') . $this->invoiceVoucherRefIdUtil->getLastId('purchases');
        $purchase->po_pending_qty = $request->total_pending;
        $purchase->po_received_qty = $request->total_received;
        $purchase->is_purchased = $request->total_received > 0 ? 1 : $purchase->is_purchased;
        $purchase->date = $request->date;
        $purchase->report_date = date('Y-m-d', strtotime($request->date));
        $purchase->save();

        // Update Purchase order Product
        $index = 0;
        foreach ($request->product_ids as $product_id) {
            $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : NULL;
            $purchaseOrderProduct = PurchaseOrderProduct::where('purchase_id', $purchase->id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();
            if ($purchaseOrderProduct) {
                $purchaseOrderProduct->pending_quantity = (float)$request->pending_quantities[$index];
                $purchaseOrderProduct->received_quantity = (float)$request->received_quantities[$index];
                $purchaseOrderProduct->save();
            }
            $index++;
        }

        // Add received product to purchase products table
        foreach ($purchase->purchase_order_products as $purchase_order_product) {
            $purchaseProduct = PurchaseProduct::where('purchase_id', $purchase->id)->where('product_order_product_id', $purchase_order_product->id)->first();
            if ($purchaseProduct) {
                $purchaseProduct->quantity = $purchase_order_product->received_quantity;
                $purchaseProduct->save();
            } else {
                if ($purchase_order_product->received_quantity != 0) {
                    $addPurchaseProduct = new PurchaseProduct();
                    $addPurchaseProduct->purchase_id = $purchase->id;
                    $addPurchaseProduct->product_order_product_id = $purchase_order_product->id;
                    $addPurchaseProduct->product_id = $purchase_order_product->product_id;
                    $addPurchaseProduct->product_variant_id = $purchase_order_product->product_variant_id;
                    $addPurchaseProduct->quantity = $purchase_order_product->received_quantity;
                    $addPurchaseProduct->unit = $purchase_order_product->unit;
                    $addPurchaseProduct->unit_cost = $purchase_order_product->unit_cost;
                    $addPurchaseProduct->unit_discount = $purchase_order_product->unit_discount;
                    $addPurchaseProduct->unit_cost_with_discount = $purchase_order_product->unit_cost_with_discount;
                    //$addPurchaseProduct->tax_id = $purchase_order_product->tax_id;
                    $addPurchaseProduct->unit_tax_percent = $purchase_order_product->unit_tax_percent;
                    $addPurchaseProduct->unit_tax = $purchase_order_product->unit_tax;
                    $addPurchaseProduct->net_unit_cost = $purchase_order_product->net_unit_cost;
                    $addPurchaseProduct->line_total = $purchase_order_product->line_total;
                    $addPurchaseProduct->profit_margin = $purchase_order_product->profit_margin;
                    $addPurchaseProduct->selling_price = $purchase_order_product->selling_price;
                    $addPurchaseProduct->lot_no = $purchase_order_product->lot_no;
                    $addPurchaseProduct->save();
                }
            }
        }

        // Add purchase payment
        if ($request->paying_amount > 0) {
            $addPurchasePayment = new PurchasePayment();
            $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : '') . date('my') . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');
            $addPurchasePayment->purchase_id = $purchase->id;
            $addPurchasePayment->account_id = $request->account_id;
            $addPurchasePayment->pay_mode = $request->payment_method;
            $addPurchasePayment->paid_amount = $request->paying_amount;
            $addPurchasePayment->date = $request->date;
            $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
            $addPurchasePayment->month = date('F');
            $addPurchasePayment->year = date('Y');
            $addPurchasePayment->note = $request->payment_note;
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

        $purchase_products = DB::table('purchase_products')->where('purchase_id', $purchase->id)->get();
        if (count($purchase_products) > 0) {
            foreach ($purchase_products as $purchase_product) {
                $this->ProductStockUtil->adjustMainProductAndVariantStock($purchase_product->product_id, $purchase_product->product_variant_id);
                if ($purchase->warehouse_id) {
                    $this->productStockUtil->addWarehouseProduct($purchase_product->product_id, $purchase_product->product_variant_id, $request->warehouse_id);
                    $this->ProductStockUtil->adjustWarehouseStock($purchase_product->product_id, $purchase_product->product_variant_id, $request->warehouse_id);
                } else if ($purchase->branch_id) {
                    $this->ProductStockUtil->addBranchProduct($purchase_product->product_id, $purchase_product->product_variant_id, auth()->user()->branch_id);
                    $this->ProductStockUtil->adjustBranchStock($purchase_product->product_id, $purchase_product->product_variant_id, auth()->user()->branch_id);
                } else {
                    $this->ProductStockUtil->adjustMainBranchStock($purchase_product->product_id, $purchase_product->product_variant_id);
                }
            }
        }

        $this->supplierUtil->adjustSupplierForSalePaymentDue($purchase->supplier_id);
        return response()->json('Successfully order receiving is modified.');
    }
}
