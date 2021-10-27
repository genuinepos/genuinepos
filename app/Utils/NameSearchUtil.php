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
            ->where('status', 1)->orderBy('id', 'desc')
            ->get();

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

        if ($branch_id) {
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
        } else {
            $mb_product_stock = DB::table('products')
                ->where('id', $product_id)
                ->first();

            if ($mb_product_stock->mb_stock > 0) {
                return response()->json($mb_product_stock->mb_stock);
            } else {
                return response()->json(['errorMsg' => 'Stock is not available of this product(variant) in this branch/shop']);
            }
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

        if ($branch_id) {
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
        } else {
            $mb_variant_stock = DB::table('product_variants')
                ->where('id', $variant_id)
                ->where('product_id', $product_id)
                ->first();

            if ($mb_variant_stock->mb_stock > 0) {
                return response()->json($mb_variant_stock->mb_stock);
            } else {
                return response()->json(['errorMsg' => 'Stock is not available of this product(variant) in this Shop/Business Location']);
            }
        }
    }
}
