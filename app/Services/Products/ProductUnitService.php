<?php

namespace App\Services\Products;

use App\Models\Products\ProductUnit;

class ProductUnitService
{
    public function addProductUnits(object $request, int $productId): void
    {
        foreach ($request->base_unit_ids as $index => $base_unit_id) {

            $addProductUnit = new ProductUnit();
            $addProductUnit->product_id = $productId;
            $addProductUnit->base_unit_id = $base_unit_id;
            $addProductUnit->base_unit_multiplier = $request->base_unit_multipliers[$index];
            $addProductUnit->assigned_unit_quantity = $request->assigned_unit_quantities[$index];
            $addProductUnit->assigned_unit_id = $request->assigned_unit_ids[$index];
            $addProductUnit->unit_cost_exc_tax = $request->assigned_unit_costs_exc_tax[$index];
            $addProductUnit->unit_cost_inc_tax = $request->assigned_unit_costs_inc_tax[$index];
            $addProductUnit->unit_price_exc_tax = $request->assigned_unit_prices_exc_tax[$index];
            $addProductUnit->save();
        }
    }

    public function addProductVariantUnits(object $request, int $productId, int $variantId, int $variantIndexNumber): void
    {
        foreach ($request->variant_base_unit_ids[$variantIndexNumber] as $index => $variant_base_unit_id) {

            $addProductVariantUnit = new ProductUnit();
            $addProductVariantUnit->product_id = $productId;
            $addProductVariantUnit->variant_id = $variantId;
            $addProductVariantUnit->base_unit_id = $variant_base_unit_id;
            $addProductVariantUnit->base_unit_multiplier = $request->variant_base_unit_multipliers[$variantIndexNumber][$index];
            $addProductVariantUnit->assigned_unit_quantity = $request->variant_assigned_unit_quantities[$variantIndexNumber][$index];
            $addProductVariantUnit->assigned_unit_id = $request->variant_assigned_unit_ids[$variantIndexNumber][$index];
            $addProductVariantUnit->unit_cost_exc_tax = $request->variant_assigned_unit_costs_exc_tax[$variantIndexNumber][$index];
            $addProductVariantUnit->unit_cost_inc_tax = $request->variant_assigned_unit_costs_inc_tax[$variantIndexNumber][$index];
            $addProductVariantUnit->unit_price_exc_tax = $request->variant_assigned_unit_prices_exc_tax[$variantIndexNumber][$index];
            $addProductVariantUnit->save();
        }
    }
}
