<?php

namespace App\Services\Products;

use App\Models\Products\PriceGroupUnit;

class PriceGroupUnitService
{
    public function addOrUpdatePriceGroupUnitsForSingleProduct(object $request, int $priceGroupId, int $priceGroupProductId, int $productId): void
    {
        foreach ($request->multiple_unit_assigned_unit_ids[$priceGroupId] as $index => $multipleUnitAssignedUnitId) {

            $addOrUpdatePriceGroupUnit = '';
            $priceGroupUnit = PriceGroupUnit::where('price_group_product_id', $priceGroupProductId)->where('assigned_unit_id', $multipleUnitAssignedUnitId)->first();

            if (isset($priceGroupUnit)) {

                $addOrUpdatePriceGroupUnit = $priceGroupUnit;
            } else {

                $addOrUpdatePriceGroupUnit = new PriceGroupUnit();
            }

            $unitPriceExcTax = isset($request->multiple_unit_prices_exc_tax[$priceGroupId][$index]) ? $request->multiple_unit_prices_exc_tax[$priceGroupId][$index] : 0;
            $addOrUpdatePriceGroupUnit->price_group_product_id = $priceGroupProductId;
            $addOrUpdatePriceGroupUnit->product_id = $productId;
            $addOrUpdatePriceGroupUnit->assigned_unit_id = $multipleUnitAssignedUnitId;
            $addOrUpdatePriceGroupUnit->unit_price_exc_tax = $unitPriceExcTax;
            $addOrUpdatePriceGroupUnit->save();
        }
    }

    public function addOrUpdatePriceGroupUnitsForVariant(object $request, int $priceGroupId, int $priceGroupProductId, int $productId, ?int $variantId = null): void
    {
        foreach ($request->multiple_unit_assigned_unit_ids[$priceGroupId][$variantId] as $index => $multipleUnitAssignedUnitId) {

            $addOrUpdatePriceGroupUnit = '';
            $priceGroupUnit = PriceGroupUnit::where('price_group_product_id', $priceGroupProductId)->where('assigned_unit_id', $multipleUnitAssignedUnitId)->first();

            if (isset($priceGroupUnit)) {

                $addOrUpdatePriceGroupUnit = $priceGroupUnit;
            } else {

                $addOrUpdatePriceGroupUnit = new PriceGroupUnit();
            }

            $unitPriceExcTax = isset($request->multiple_unit_prices_exc_tax[$priceGroupId][$variantId][$index]) ? $request->multiple_unit_prices_exc_tax[$priceGroupId][$variantId][$index] : 0;
            $addOrUpdatePriceGroupUnit->price_group_product_id = $priceGroupProductId;
            $addOrUpdatePriceGroupUnit->product_id = $productId;
            $addOrUpdatePriceGroupUnit->variant_id = $variantId;
            $addOrUpdatePriceGroupUnit->assigned_unit_id = $multipleUnitAssignedUnitId;
            $addOrUpdatePriceGroupUnit->unit_price_exc_tax = $unitPriceExcTax;
            $addOrUpdatePriceGroupUnit->save();
        }
    }
}
