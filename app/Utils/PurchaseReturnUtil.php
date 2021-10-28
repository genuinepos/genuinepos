<?php

namespace App\Utils;


use App\Models\Purchase;
use App\Utils\PurchaseUtil;
use App\Models\PurchaseReturn;
use App\Models\PurchaseProduct;
use App\Utils\ProductStockUtil;
use App\Models\PurchaseReturnProduct;

class PurchaseReturnUtil
{
    public $purchaseUtil;
    public $productStockUtil;
    public $supplierUtil;

    public function __construct(
        PurchaseUtil $purchaseUtil,
        ProductStockUtil $productStockUtil,
        SupplierUtil $supplierUtil,
    ) {
        $this->purchaseUtil = $purchaseUtil;
        $this->productStockUtil = $productStockUtil;
        $this->supplierUtil = $supplierUtil;
    }

    public function updatePurchaseInvoiceWiseReturn($purchaseId, $purchaseReturn, $request, $invoicePrefix, $invoiceId)
    {
        $purchase_product_ids = $request->purchase_product_ids;
        $return_quantities = $request->return_quantities;
        $return_subtotals = $request->return_subtotals;
        $units = $request->units;

        $purchase = Purchase::where('id', $purchaseId)->first();
        //Update purchase and supplier purchase return due
        $purchaseDue = $purchase->total_purchase_amount - $purchase->paid;
        $purchaseReturnDue = $request->total_return_amount - $purchaseDue;

        // Update purchase return
        $purchaseReturn->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . date('my') . $invoiceId;
        if ($purchase->warehouse_id) {
            $purchaseReturn->warehouse_id = $purchase->warehouse_id;
        } else {
            $purchaseReturn->branch_id = $purchase->branch_id;
        }

        $purchaseReturn->supplier_id = $purchase->supplier_id;
        $purchaseReturn->total_return_amount = $request->total_return_amount;

        if ($purchaseReturnDue > 0) {
            $purchaseReturn->total_return_due = $purchaseReturnDue - $purchaseReturn->total_return_due_received;
        } else {
            $purchaseReturn->total_return_due = 0;
        }

        $purchaseReturn->date = $request->date;
        $purchaseReturn->report_date = date('Y-m-d', strtotime($request->date));
        $purchaseReturn->save();

        // update purchase return products stock
        $index = 0;
        foreach ($purchase_product_ids as $purchase_product_id) {
            $returnProduct = PurchaseReturnProduct::where('purchase_return_id', $purchaseReturn->id)
                ->where('purchase_product_id', $purchase_product_id)->first();

            $returnProduct->return_qty = $return_quantities[$index];
            $returnProduct->unit = $units[$index];
            $returnProduct->return_subtotal = $return_subtotals[$index];
            $returnProduct->save();
            $index++;
        }

        foreach ($purchase->purchase_products as $purchase_product) {
            $this->productStockUtil->adjustMainProductAndVariantStock($purchase_product->product_id, $purchase_product->product_variant_id);
            if ($purchase->warehouse_id) {
                $this->productStockUtil->adjustWarehouseStock($purchase_product->product_id, $purchase_product->product_variant_id, $purchase->warehouse_id);
            } else {
                $this->productStockUtil->adjustBranchStock($purchase_product->product_id, $purchase_product->product_variant_id, $purchase->branch_id);
            }
        }

        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
        $this->supplierUtil->adjustSupplierForSalePaymentDue($purchase->supplier_id);
    }

    public function storePurchaseInvoiceWiseReturn($purchaseId, $request, $invoicePrefix, $invoiceId)
    {
        $purchase_product_ids = $request->purchase_product_ids;
        $return_quantities = $request->return_quantities;
        $return_subtotals = $request->return_subtotals;
        $units = $request->units;

        $purchase = Purchase::where('id', $purchaseId)->first();
        $purchase->is_return_available = 1;
        $purchaseDue = $purchase->total_purchase_amount - $purchase->paid;
        $purchaseReturnDue = $request->total_return_amount - $purchaseDue;

        $addPurchaseReturn = new PurchaseReturn();
        $addPurchaseReturn->purchase_id = $purchase->id;
        $addPurchaseReturn->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . date('my') . $invoiceId;
        if ($purchase->warehouse_id) {
            $addPurchaseReturn->warehouse_id = $purchase->warehouse_id;
        } else {
            $addPurchaseReturn->branch_id = $purchase->branch_id;
        }

        $addPurchaseReturn->admin_id = auth()->user()->id;
        $addPurchaseReturn->total_return_amount = $request->total_return_amount;
        if ($purchaseReturnDue > 0) {
            $addPurchaseReturn->total_return_due = $purchaseReturnDue;
        } else {
            $addPurchaseReturn->total_return_due = 0;
        }

        $addPurchaseReturn->return_type = 1;
        $addPurchaseReturn->date = $request->date;
        $addPurchaseReturn->report_date = date('Y-m-d', strtotime($request->date));
        $addPurchaseReturn->month = date('F');
        $addPurchaseReturn->year = date('Y');
        $addPurchaseReturn->save();

        // Add purchase return products
        $index = 0;
        foreach ($purchase_product_ids as $purchase_product_id) {
            // Update purchase product quantity for adjustment
            $purchaseProduct = PurchaseProduct::where('id', $purchase_product_id)->first();
            $addReturnProduct = new PurchaseReturnProduct();
            $addReturnProduct->purchase_return_id = $addPurchaseReturn->id;
            $addReturnProduct->purchase_product_id = $purchase_product_id;
            $addReturnProduct->product_id =  $purchaseProduct->product_id;
            $addReturnProduct->product_variant_id = $purchaseProduct->product_variant_id ? $purchaseProduct->product_variant_id : NULL;
            $addReturnProduct->return_qty = $return_quantities[$index];
            $addReturnProduct->unit = $units[$index];
            $addReturnProduct->return_subtotal = $return_subtotals[$index];
            $addReturnProduct->save();
            $index++;
        }

        foreach ($purchase->purchase_products as $purchase_product) {
            $this->productStockUtil->adjustMainProductAndVariantStock($purchase_product->product_id, $purchase_product->product_variant_id);

            if ($purchase->warehouse_id) {
                $this->productStockUtil->adjustWarehouseStock($purchase_product->product_id, $purchase_product->product_variant_id, $purchase->warehouse_id);
            } else {
                $this->productStockUtil->adjustBranchStock($purchase_product->product_id, $purchase_product->product_variant_id, $purchase->branch_id);
            }
        }

        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
        $this->supplierUtil->adjustSupplierForSalePaymentDue($purchase->supplier_id);
    }
}
