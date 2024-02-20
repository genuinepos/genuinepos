<?php

namespace App\Services\Purchases;

use App\Models\Purchases\PurchaseOrderProduct;

class PurchaseOrderProductService
{
    public function addPurchaseOrderProduct(object $request, int|string $isEditProductPrice, int $purchaseOrderId): void
    {
        $index = 0;
        foreach ($request->product_ids as $productId) {

            $addPurchaseProduct = new PurchaseOrderProduct();
            $addPurchaseProduct->purchase_id = $purchaseOrderId;
            $addPurchaseProduct->product_id = $productId;
            $addPurchaseProduct->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $addPurchaseProduct->description = $request->descriptions[$index];
            $addPurchaseProduct->ordered_quantity = $request->quantities[$index];
            $addPurchaseProduct->pending_quantity = $request->quantities[$index];
            $addPurchaseProduct->unit_id = $request->unit_ids[$index];
            $addPurchaseProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
            $addPurchaseProduct->unit_discount = $request->unit_discounts[$index];
            $addPurchaseProduct->unit_discount_type = $request->unit_discount_types[$index];
            $addPurchaseProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
            $addPurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
            $addPurchaseProduct->subtotal = $request->subtotals[$index];
            $addPurchaseProduct->tax_ac_id = $request->tax_ac_ids[$index];
            $addPurchaseProduct->unit_tax_type = $request->tax_types[$index];
            $addPurchaseProduct->unit_tax_percent = $request->unit_tax_percents[$index];
            $addPurchaseProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
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

    public function updatePurchaseOrderProducts(object $request, int|string $isEditProductPrice, int $purchaseOrderId): void
    {
        $index = 0;
        foreach ($request->product_ids as $productId) {

            $addOrUpdatePurchaseProduct = '';

            $purchaseOrderProduct = PurchaseOrderProduct::where('id', $request->purchase_order_product_ids[$index])->first();
            if ($purchaseOrderProduct) {

                $addOrUpdatePurchaseProduct = $purchaseOrderProduct;
            } else {

                $addOrUpdatePurchaseProduct = new PurchaseOrderProduct();
            }

            $addOrUpdatePurchaseProduct->purchase_id = $purchaseOrderId;
            $addOrUpdatePurchaseProduct->product_id = $productId;
            $addOrUpdatePurchaseProduct->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $addOrUpdatePurchaseProduct->description = $request->descriptions[$index];
            $addOrUpdatePurchaseProduct->ordered_quantity = $request->quantities[$index];
            $addOrUpdatePurchaseProduct->pending_quantity = $request->quantities[$index];
            $addOrUpdatePurchaseProduct->unit_id = $request->unit_ids[$index];
            $addOrUpdatePurchaseProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
            $addOrUpdatePurchaseProduct->unit_discount = $request->unit_discounts[$index];
            $addOrUpdatePurchaseProduct->unit_discount_type = $request->unit_discount_types[$index];
            $addOrUpdatePurchaseProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
            $addOrUpdatePurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
            $addOrUpdatePurchaseProduct->subtotal = $request->subtotals[$index];
            $addOrUpdatePurchaseProduct->tax_ac_id = $request->tax_ac_ids[$index];
            $addOrUpdatePurchaseProduct->unit_tax_type = $request->tax_types[$index];
            $addOrUpdatePurchaseProduct->unit_tax_percent = $request->unit_tax_percents[$index];
            $addOrUpdatePurchaseProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
            $addOrUpdatePurchaseProduct->net_unit_cost = $request->net_unit_costs[$index];
            $addOrUpdatePurchaseProduct->line_total = $request->linetotals[$index];

            if ($isEditProductPrice == '1') {

                $addOrUpdatePurchaseProduct->profit_margin = $request->profits[$index];
                $addOrUpdatePurchaseProduct->selling_price = $request->selling_prices[$index];
            }

            $addOrUpdatePurchaseProduct->is_delete_in_update = 0;
            $addOrUpdatePurchaseProduct->save();
            $index++;
        }
    }

    public function purchaseOrderProducts(array $with = null): ?object
    {
        $query = PurchaseOrderProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
