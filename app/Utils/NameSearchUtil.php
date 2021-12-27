<?php

namespace App\Utils;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class NameSearchUtil
{
    public function nameSearching($keyword)
    {
        $namedProducts = '';
        $namedProducts = Product::with(['product_variants', 'tax', 'unit'])
            ->where('name', 'LIKE',  $keyword . '%')
            ->where('status', 1)->select(
                'id',
                'name',
                'product_code',
                'is_combo',
                'is_featured',
                'is_for_sale',
                'is_manage_stock',
                'is_purchased',
                'is_show_emi_on_pos',
                'is_variant',
                'offer_price',
                'product_cost',
                'product_cost_with_tax',
                'product_price',
                'profit',
                'quantity',
                'tax_id',
                'tax_type',
                'thumbnail_photo',
                'type',
                'unit_id',
            )->orderBy('id', 'desc')->limit(25)->get();

        if ($namedProducts && count($namedProducts) > 0) {
            return response()->json(['namedProducts' => $namedProducts]);
        } else {
            return response()->json(['NotFoundMsg' => 'Not Found.']);
        }
    }

    public function checkBranchSingleProductStock($product_id, $branch_id)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock')
            ->first();

        if ($product->is_manage_stock == 0) {
            return response()->json(PHP_INT_MAX);
        }

        $productBranch = DB::table('product_branches')->where('product_id', $product_id)->where('branch_id', $branch_id)->first();
        if ($productBranch) {
            if ($productBranch->product_quantity > 0) {
                return response()->json($productBranch->product_quantity);
            } else {
                return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this shop/branch']);
            }
        } else {
            return response()->json(['errorMsg' => 'This product is not available in this shop/branch.']);
        }
        
    }

    public function checkBranchVariantProductStock($product_id, $variant_id, $branch_id)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock')
            ->first();

        if ($product->is_manage_stock == 0) {
            return response()->json(PHP_INT_MAX);
        }

        $productBranch = DB::table('product_branches')->where('branch_id', $branch_id)->where('product_id', $product_id)->first();
        if ($productBranch) {
            $productBranchVariant = DB::table('product_branch_variants')->where('product_branch_id', $productBranch->id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();
            if ($productBranchVariant) {
                if ($productBranchVariant->variant_quantity > 0) {
                    return response()->json($productBranchVariant->variant_quantity);
                } else {
                    return response()->json(['errorMsg' => 'Stock is out of this product(variant) from this Shop/Business Location']);
                }
            } else {
                return response()->json(['errorMsg' => 'This variant is not available in this Shop/Business Location.']);
            }
        } else {
            return response()->json(['errorMsg' => 'This product is not available in this Shop/Business Location.']);
        }
        
    }
}
