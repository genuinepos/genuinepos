<?php

namespace App\Services\Products;

use App\Models\Products\PriceGroupProduct;
use Illuminate\Support\Facades\DB;

class ManagePriceGroupService
{
    public function addOrUpdatePriceGroupProduct(
        object $request,
        int $priceGroupId,
        int $productId,
        int|string $variantId = null,
        ?float $price = null
    ): object {
        (float) $__price = $price;
        $__variantId = $variantId != 'noid' ? $variantId : null;

        $addOrUpdatePriceGroup = null;
        $priceGroup = PriceGroupProduct::where('price_group_id', $priceGroupId)
            ->where('product_id', $productId)
            ->where('variant_id', $__variantId)->first();

        if ($priceGroup) {

            $addOrUpdatePriceGroup = $priceGroup;
        } else {

            $addOrUpdatePriceGroup = new PriceGroupProduct();
        }

        $addOrUpdatePriceGroup = new PriceGroupProduct();
        $addOrUpdatePriceGroup->price_group_id = $priceGroupId;
        $addOrUpdatePriceGroup->product_id = $productId;
        $addOrUpdatePriceGroup->variant_id = $__variantId;
        $addOrUpdatePriceGroup->price = $__price != null ? $__price : null;
        $addOrUpdatePriceGroup->save();

        return $addOrUpdatePriceGroup;
    }

    public function priceGroupProducts(?array $with = null)
    {
        $query = PriceGroupProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
        return DB::table('price_group_products')->get();
    }
}
