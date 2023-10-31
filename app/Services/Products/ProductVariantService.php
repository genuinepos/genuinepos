<?php

namespace App\Services\Products;

use App\Enums\IsDeleteInUpdate;
use Intervention\Image\Facades\Image;
use App\Models\Products\ProductVariant;

class ProductVariantService
{
    public function addProductVariants(object $request, int $productId): void
    {
        foreach ($request->variant_combinations as $index => $variantCombination) {

            $addVariant = new ProductVariant();
            $addVariant->product_id = $productId;
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
        }
    }

    public function updateProductVariants(object $request, int $productId): void
    {
        foreach ($request->variant_combinations as $index => $variantCombination) {

            $addOrUpdateVariant = '';

            $variant = $this->singleVariant(id: $request->product_variant_ids[$index]);

            if ($variant) {

                $addOrUpdateVariant = $variant;
            } else {

                $addOrUpdateVariant = new ProductVariant();
            }

            $addOrUpdateVariant->product_id = $productId;
            $addOrUpdateVariant->variant_name = $variantCombination;
            $addOrUpdateVariant->variant_code = $request->variant_codes[$index];
            $addOrUpdateVariant->variant_cost = $request->variant_costings[$index];
            $addOrUpdateVariant->variant_cost_with_tax = $request->variant_costings_with_tax[$index];
            $addOrUpdateVariant->variant_profit = $request->variant_profits[$index];
            $addOrUpdateVariant->variant_price = $request->variant_prices_exc_tax[$index];
            $addOrUpdateVariant->is_delete_in_update = IsDeleteInUpdate::No->value;

            if (isset($request->variant_image[$index])) {

                if (isset($addOrUpdateVariant->variant_image)) {

                    if (file_exists(public_path('uploads/product/variant_image/' . $addOrUpdateVariant->variant_image))) {

                        unlink(public_path('uploads/product/variant_image/' . $addOrUpdateVariant->variant_image));
                    }
                }

                $variantImage = $request->variant_image[$index];
                $variantImageName = uniqid() . '.' . $variantImage->getClientOriginalExtension();
                $path = public_path('uploads/product/variant_image');

                if (!file_exists($path)) {

                    mkdir($path);
                }

                Image::make($variantImage)->resize(250, 250)->save($path . '/' . $variantImageName);
                $addOrUpdateVariant->variant_image = $variantImageName;
            }

            $addOrUpdateVariant->save();
        }
    }

    public function deleteUnusedProductVariants(int $productId): void
    {
        $deleteAbleVariants = $this->variants()->where('product_id', $productId)
            ->where('is_delete_in_update', IsDeleteInUpdate::Yes->value)->get();

        foreach ($deleteAbleVariants as $deleteAbleVariant) {

            if (isset($deleteAbleVariant->variant_image)) {

                if (file_exists(public_path('uploads/product/variant_image/' . $deleteAbleVariant->variant_image))) {

                    unlink(public_path('uploads/product/variant_image/' . $deleteAbleVariant->variant_image));
                }
            }
            $deleteAbleVariant->delete();
        }
    }

    public function singleVariant(?int $id, array $with = null): ?object
    {
        $query = ProductVariant::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function variants(array $with = null): ?object
    {
        $query = ProductVariant::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
