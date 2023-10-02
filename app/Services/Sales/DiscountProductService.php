<?php

namespace App\Services\Sales;

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
}

