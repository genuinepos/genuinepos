<?php

namespace App\Utils;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class ProductStockUtil
{
    public function adjustMainProductAndVariantStock($product_id, $variant_id)
    {
        $productOpeningStock = DB::table('product_opening_stocks')
            ->where('product_id', $product_id)
            ->select(DB::raw('sum(quantity) as po_stock'))
            ->groupBy('product_id')->get();

        $productPurchase = DB::table('purchase_products')
        ->where('purchase_products.product_id', $product_id)
            ->select(DB::raw('sum(quantity) as total_purchase'))->groupBy('purchase_products.product_id')->get();

        $productSale = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->where('sale_products.product_id', $product_id)
            ->where('sales.status', 1)
            ->select(DB::raw('sum(quantity) as total_sale'))
            ->groupBy('sale_products.product_id')->get();

        $productCurrentStock = $productPurchase->sum('total_purchase')
            + $productOpeningStock->sum('po_stock')
            - $productSale->sum('total_sale');

        $product = Product::where('id', $product_id)->first();
        $product->quantity = $productCurrentStock;
        $product->number_of_sale = $productSale->sum('total_sale');
        $product->save();

        if ($variant_id) {
            $variantOpeningStock = DB::table('product_opening_stocks')
                ->where('product_variant_id', $variant_id)
                ->select(DB::raw('sum(quantity) as vo_stock'))
                ->groupBy('product_variant_id')->get();

            $variantPurchase = DB::table('purchase_products')
                ->where('purchase_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(quantity) as total_purchase'))
                ->groupBy('purchase_products.product_variant_id')
                ->get();

            $variantSale = DB::table('sale_products')
                ->where('sale_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(quantity) as total_sale'))
                ->groupBy('sale_products.product_id')->get();

            $variantCurrentStock = $variantPurchase->sum('total_purchase')
                + $variantOpeningStock->sum('vo_stock')
                - $variantSale->sum('total_sale');
            
            $variant = ProductVariant::where('id', $variant_id)->first();
            $variant->variant_quantity = $variantCurrentStock;
            $variant->save();
        }
    }
}
