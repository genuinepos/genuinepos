<?php

namespace App\Utils;

use App\Models\PurchaseOrderProduct;

class PurchaseOrderProductUtil
{
    public function addPurchaseOrderProduct($request, $isEditProductPrice, $orderId)
    {
        $index = 0;
        foreach ($request->product_ids as $productId) {

            $addPurchaseProduct = new PurchaseOrderProduct();
            $addPurchaseProduct->purchase_id = $orderId;
            $addPurchaseProduct->product_id = $productId;
            $addPurchaseProduct->product_variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $addPurchaseProduct->description = $request->descriptions[$index];
            $addPurchaseProduct->order_quantity = $request->quantities[$index];
            $addPurchaseProduct->pending_quantity = $request->quantities[$index];
            $addPurchaseProduct->unit = $request->units[$index];
            $addPurchaseProduct->unit_cost = $request->unit_costs_exc_tax[$index];
            $addPurchaseProduct->unit_discount = $request->unit_discounts[$index];
            $addPurchaseProduct->unit_discount_type = $request->unit_discount_types[$index];
            $addPurchaseProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
            $addPurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
            $addPurchaseProduct->subtotal = $request->subtotals[$index];
            $addPurchaseProduct->tax_type = $request->tax_types[$index];
            $addPurchaseProduct->unit_tax_percent = $request->unit_tax_percents[$index];
            $addPurchaseProduct->unit_tax = $request->unit_tax_amounts[$index];
            $addPurchaseProduct->net_unit_cost = $request->net_unit_costs[$index];
            $addPurchaseProduct->line_total = $request->linetotals[$index];

            if ($isEditProductPrice == '1') {

                $addPurchaseProduct->profit_margin = $request->profits[$index];
                $addPurchaseProduct->selling_price = $request->selling_prices[$index];
            }

            $addPurchaseProduct->save();
            $index++;
        }
    }
}
