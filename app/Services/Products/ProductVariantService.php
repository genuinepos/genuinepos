<?php

namespace App\Services\Products;

use App\Models\ProductVariant;
use Intervention\Image\Facades\Image;

class ProductVariantService
{
    function addProductVariants(object $request): array
    {
        $variantId = [];

        $index = 0;
        foreach ($request->variant_combinations as $variantCombination) {

            $addVariant = new ProductVariant();
            $addVariant->product_id = $addProduct->id;
            $addVariant->variant_name = $variantCombination;
            $addVariant->variant_code = $request->variant_codes[$index];
            $addVariant->variant_cost = $request->variant_costings[$index];
            $addVariant->variant_cost_with_tax = $request->variant_costings_with_tax[$index];
            $addVariant->variant_profit = $request->variant_profits[$index];
            $addVariant->variant_price = $request->variant_prices_exc_tax[$index];

            if (isset($request->variant_image[$index])) {

                $variantImage = $request->variant_image[$index];
                $variantImageName = uniqid() . '.' . $variantImage->getClientOriginalExtension();
                $path = public_path('uploads/product/variant_image');

                if (!file_exists($path)) {

                    mkdir($path);
                }

                Image::make($variantImage)->resize(250, 250)->save($path . '/' . $variantImageName);
                $addVariant->variant_image = $variantImageName;
            }

            $addVariant->save();

            array_push($variantIds, $addVariant->id);

            $index++;
        }


        return $variantIds;
    }
}