<?php

namespace App\Services\Products;

use App\Enums\IsDeleteInUpdate;
use App\Models\Products\ProductVariant;
use Intervention\Image\Facades\Image;

class ProductVariantService
{
    public function addProductVariant(object $request, int $productId, int $index): object
    {
        $addVariant = new ProductVariant();
        $addVariant->product_id = $productId;
        $addVariant->variant_name = $request->variant_combinations[$index];
        $addVariant->variant_code = $request->variant_codes[$index];
        $addVariant->variant_cost = $request->variant_costs_exc_tax[$index];
        $addVariant->variant_cost_with_tax = $request->variant_costs_inc_tax[$index];
        $addVariant->variant_profit = $request->variant_profits[$index];
        $addVariant->variant_price = $request->variant_prices_exc_tax[$index];

        if (isset($request->variant_image[$index])) {

            $variantImage = $request->variant_image[$index];
            $variantImageName = uniqid() . '.' . $variantImage->getClientOriginalExtension();

            $dir = public_path('uploads/' . tenant('id') . '/' . 'product/variant_image/');

            if (!\File::isDirectory($dir)) {

                \File::makeDirectory($dir, 493, true);
            }

            Image::make($variantImage)->resize(250, 250)->save($dir . $variantImageName);
            $addVariant->variant_image = $variantImageName;
        }

        $addVariant->save();

        return $addVariant;
    }

    public function updateProductVariant(object $request, int $productId, $index): object
    {
        $addOrUpdateVariant = '';

        $variant = $this->singleVariant(id: $request->product_variant_ids[$index]);

        if ($variant) {

            $addOrUpdateVariant = $variant;
        } else {

            $addOrUpdateVariant = new ProductVariant();
        }

        $addOrUpdateVariant->product_id = $productId;
        $addOrUpdateVariant->variant_name = $request->variant_combinations[$index];
        $addOrUpdateVariant->variant_code = $request->variant_codes[$index];
        $addOrUpdateVariant->variant_cost = $request->variant_costs_exc_tax[$index];
        $addOrUpdateVariant->variant_cost_with_tax = $request->variant_costs_inc_tax[$index];
        $addOrUpdateVariant->variant_profit = $request->variant_profits[$index];
        $addOrUpdateVariant->variant_price = $request->variant_prices_exc_tax[$index];
        $addOrUpdateVariant->is_delete_in_update = IsDeleteInUpdate::No->value;

        if (isset($request->variant_image[$index])) {

            $dir = public_path('uploads/' . tenant('id') . '/' . 'product/variant_image/');

            if (isset($addOrUpdateVariant->variant_image)) {

                if (file_exists($dir . $addOrUpdateVariant->variant_image)) {

                    unlink($dir . $addOrUpdateVariant->variant_image);
                }
            }

            if (!\File::isDirectory($dir)) {

                \File::makeDirectory($dir, 493, true);
            }

            $variantImage = $request->variant_image[$index];
            $variantImageName = uniqid() . '.' . $variantImage->getClientOriginalExtension();
            Image::make($variantImage)->resize(250, 250)->save($dir . $variantImageName);
            $addOrUpdateVariant->variant_image = $variantImageName;
        }

        $addOrUpdateVariant->save();

        return $addOrUpdateVariant;
    }

    public function deleteUnusedProductVariants(int $productId): void
    {
        $deleteAbleVariants = $this->variants()->where('product_id', $productId)
            ->where('is_delete_in_update', IsDeleteInUpdate::Yes->value)->get();

        foreach ($deleteAbleVariants as $deleteAbleVariant) {

            $dir = public_path('uploads/' . tenant('id') . '/' . 'product/variant_image/');

            if (isset($deleteAbleVariant->variant_image)) {

                if (file_exists($dir . $deleteAbleVariant->variant_image)) {

                    unlink($dir . $deleteAbleVariant->variant_image);
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
