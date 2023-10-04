<?php

namespace App\Services\Sales;

use App\Enums\IsDeleteInUpdate;
use Illuminate\Support\Facades\DB;
use App\Models\Sales\DiscountProduct;

class DiscountProductService
{
    public function addDiscountProducts(object $request, int $discountId): void
    {
        foreach ($request->product_ids as $product_id) {

            $addDiscountProduct = new DiscountProduct();
            $addDiscountProduct->discount_id = $discountId;
            $addDiscountProduct->product_id = $product_id;
            $addDiscountProduct->save();
        }
    }

    function updateDiscountProducts(object $request, object $discount): void
    {
        if (isset($request->product_ids) && count($request->product_ids) > 0) {

            foreach ($request->product_ids as $product_id) {

                $discountProduct = $this->discountProducts()->where('discount_id', $discount->id)
                    ->where('product_id', $product_id)->first();

                $addOrUpdateDiscountProduct = '';

                if ($discountProduct) {

                    $addOrUpdateDiscountProduct = $discountProduct;
                } else {

                    $addOrUpdateDiscountProduct = new DiscountProduct();
                }

                $addOrUpdateDiscountProduct->discount_id = $discount->id;
                $addOrUpdateDiscountProduct->product_id = $product_id;
                $addOrUpdateDiscountProduct->is_delete_in_update = IsDeleteInUpdate::No->value;
                $addOrUpdateDiscountProduct->save();
            }
        } else {

            foreach ($discount->discountProducts as $discountProduct) {

                $discountProduct->delete();
            }
        }

        // Unused discount product
        $deleteUnusedDiscountProducts = $this->discountProducts()->where('discount_id', $discount->id)
            ->where('is_delete_in_update', IsDeleteInUpdate::Yes->value)->get();;
        foreach ($deleteUnusedDiscountProducts as $deleteUnusedDiscountProduct) {

            $deleteUnusedDiscountProduct->delete();
        }
    }

    public function singleDiscountProduct(?array $with = null): ?object
    {
        $query = DiscountProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function discountProducts(?array $with = null): ?object
    {
        $query = DiscountProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
