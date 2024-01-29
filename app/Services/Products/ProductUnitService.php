<?php

namespace App\Services\Products;

use App\Enums\BooleanType;
use App\Enums\IsDeleteInUpdate;
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

    public function updateProductUnits(object $request, object $product): void
    {
        foreach ($request->base_unit_ids as $index => $base_unit_id) {

            $addOrUpdateProductUnit = new ProductUnit();

            $productUnit = $this->singleProductUnit(id: $request->product_unit_ids[$index]);
            if (isset($productUnit)) {

                $addOrUpdateProductUnit = $productUnit;
            }

            $addOrUpdateProductUnit->product_id = $product->id;
            $addOrUpdateProductUnit->base_unit_id = $base_unit_id;
            $addOrUpdateProductUnit->base_unit_multiplier = $request->base_unit_multipliers[$index];
            $addOrUpdateProductUnit->assigned_unit_quantity = $request->assigned_unit_quantities[$index];
            $addOrUpdateProductUnit->assigned_unit_id = $request->assigned_unit_ids[$index];
            $addOrUpdateProductUnit->unit_cost_exc_tax = $request->assigned_unit_costs_exc_tax[$index];
            $addOrUpdateProductUnit->unit_cost_inc_tax = $request->assigned_unit_costs_inc_tax[$index];
            $addOrUpdateProductUnit->unit_price_exc_tax = $request->assigned_unit_prices_exc_tax[$index];
            $addOrUpdateProductUnit->is_delete_in_update = IsDeleteInUpdate::No->value;
            $addOrUpdateProductUnit->save();
        }
    }

    public function addProductVariantUnits(object $request, int $productId, int $variantId, int $variantIndexNumber): void
    {
        if (isset($request->variant_base_unit_ids[$variantIndexNumber])) {

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

    public function updateProductVariantUnits(object $request, int $productId, int $variantId, int $variantIndexNumber): void
    {
        foreach ($request->variant_base_unit_ids[$variantIndexNumber] as $index => $variant_base_unit_id) {

            $addOrUpdateProductVariantUnit = new ProductUnit();

            $productVariantUnit = $this->singleProductUnit(id: $request->product_variant_unit_ids[$variantIndexNumber][$index]);

            if (isset($productVariantUnit)) {

                $addOrUpdateProductVariantUnit = $productVariantUnit;
            }

            $addOrUpdateProductVariantUnit->product_id = $productId;
            $addOrUpdateProductVariantUnit->variant_id = $variantId;
            $addOrUpdateProductVariantUnit->base_unit_id = $variant_base_unit_id;
            $addOrUpdateProductVariantUnit->base_unit_multiplier = $request->variant_base_unit_multipliers[$variantIndexNumber][$index];
            $addOrUpdateProductVariantUnit->assigned_unit_quantity = $request->variant_assigned_unit_quantities[$variantIndexNumber][$index];
            $addOrUpdateProductVariantUnit->assigned_unit_id = $request->variant_assigned_unit_ids[$variantIndexNumber][$index];
            $addOrUpdateProductVariantUnit->unit_cost_exc_tax = $request->variant_assigned_unit_costs_exc_tax[$variantIndexNumber][$index];
            $addOrUpdateProductVariantUnit->unit_cost_inc_tax = $request->variant_assigned_unit_costs_inc_tax[$variantIndexNumber][$index];
            $addOrUpdateProductVariantUnit->unit_price_exc_tax = $request->variant_assigned_unit_prices_exc_tax[$variantIndexNumber][$index];
            $addOrUpdateProductVariantUnit->is_delete_in_update = IsDeleteInUpdate::No->value;
            $addOrUpdateProductVariantUnit->save();
        }
    }

    public function deleteUnusedProductAndVariantUnits(int $productId): void
    {
        $unusedDeletableProductUnits = $this->productUnits()->where('product_id', $productId)->where('is_delete_in_update', IsDeleteInUpdate::Yes->value)->get();

        foreach ($unusedDeletableProductUnits as $unusedDeletableProductUnit) {

            $unusedDeletableProductUnit->delete();
        }
    }

    function singleProductUnit(?int $id, ?array $with = null): ?object
    {
        $query = ProductUnit::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    function productUnits(?array $with = null): object
    {
        $query = ProductUnit::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
