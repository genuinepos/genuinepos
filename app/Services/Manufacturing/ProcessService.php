<?php

namespace App\Services\Manufacturing;

use App\Models\Products\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Manufacturing\Process;

class ProcessService
{
    public function process(array $with = null): ?object
    {
        $query = Process::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function getProcessableProductForCreate(object $request): object
    {
        $product = [];
        $productAndVariantId = explode('-', $request->product_id);
        $productId = $productAndVariantId[0];
        $variantId = $productAndVariantId[1];
        if ($variantId != 'noid') {

            $variantProduct = DB::table('product_variants')->where('product_variants.id', $variantId)
                ->leftJoin('products', 'product_variants.product_id', 'products.id')
                ->leftJoin('units', 'products.unit_id', 'units.id')
                ->select(
                    'product_variants.id as variant_id',
                    'product_variants.variant_name',
                    'product_variants.variant_code',
                    'products.id as product_id',
                    'products.name',
                    'products.id as product_id',
                    'products.product_code',
                    'units.id as unit_id',
                    'units.name as unit_name',
                )->first();

            $product['product_id'] = $variantProduct->product_id;
            $product['unit_id'] = $variantProduct->unit_id;
            $product['unit_name'] = $variantProduct->unit_name;
            $product['product_name'] = $variantProduct->name;
            $product['product_code'] = $variantProduct->product_code;
            $product['variant_id'] = $variantProduct->variant_id;
            $product['variant_name'] = $variantProduct->variant_name;
            $product['variant_code'] = $variantProduct->variant_code;
        } else {

            $product = Product::with('unit')->where('id', $productId)
                ->select('id', 'unit_id', 'name', 'product_code')
                ->first();

            $product['product_id'] = $product->id;
            $product['unit_id'] = $product?->unit?->id;
            $product['unit_name'] = $product?->unit?->name;
            $product['product_name'] = $product->name;
            $product['product_code'] = $product->product_code;
            $product['variant_id'] = null;
            $product['variant_name'] = null;
            $product['variant_code'] = null;
        }

        return $product;
    }
}
