<?php

namespace App\Services\Products;

use App\Models\PriceGroupProduct;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class ManagePriceGroupService
{
    function addOrUpdateManagePriceGroups(object $request)
    {
        $variant_ids = $request->variant_ids;
        $index = 0;
        foreach ($request->product_ids as $product_id) {

            foreach ($request->group_prices as $key => $group_price) {

                (float) $__group_price = $group_price[$product_id][$variant_ids[$index]];
                $__variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : null;
                $updatePriceGroup = PriceGroupProduct::where('price_group_id', $key)->where('product_id', $product_id)->where('variant_id', $__variant_id)->first();

                if ($updatePriceGroup) {

                    $updatePriceGroup->price = $__group_price != null ? $__group_price : null;
                    $updatePriceGroup->save();
                } else {

                    $addPriceGroup = new PriceGroupProduct();
                    $addPriceGroup->price_group_id = $key;
                    $addPriceGroup->product_id = $product_id;
                    $addPriceGroup->variant_id = $__variant_id;
                    $addPriceGroup->price = $__group_price != null ? $__group_price : null;
                    $addPriceGroup->save();
                }
            }
            $index++;
        }
    }

    function priceGroupProducts(int $productId = null, ?int $variantId = null, ?int $branchId = null)
    {
        return DB::table('price_group_products')->get(['id', 'price_group_id', 'product_id', 'variant_id', 'price']);
    }
}
