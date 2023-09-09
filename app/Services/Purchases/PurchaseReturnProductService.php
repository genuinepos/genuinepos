<?php

namespace App\Services\Purchases;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnProductService
{
    public function addPurchaseReturnProduct(object $request, int $purchaseReturnId, int $index): object
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $addPurchaseReturnProduct = new PurchaseReturnProduct();
        $addPurchaseReturnProduct->purchase_return_id = $purchaseReturnId;
        $addPurchaseReturnProduct->purchase_product_id = $request->purchase_product_ids[$index];
        $addPurchaseReturnProduct->product_id = $request->product_ids[$index];
        $addPurchaseReturnProduct->variant_id = $variant_id;
        $addPurchaseReturnProduct->warehouse_id = $request->warehouse_ids[$index];
        $addPurchaseReturnProduct->return_qty = $request->return_quantities[$index];
        $addPurchaseReturnProduct->purchase_qty = $request->purchased_quantities[$index];
        $addPurchaseReturnProduct->unit_id = $request->unit_ids[$index];
        $addPurchaseReturnProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
        $addPurchaseReturnProduct->unit_discount = $request->unit_discounts[$index];
        $addPurchaseReturnProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addPurchaseReturnProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addPurchaseReturnProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addPurchaseReturnProduct->tax_type = $request->tax_types[$index];
        $addPurchaseReturnProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addPurchaseReturnProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addPurchaseReturnProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addPurchaseReturnProduct->return_subtotal = $request->subtotals[$index];
        $addPurchaseReturnProduct->save();

        return $addPurchaseReturnProduct;
    }
}
