<?php

namespace App\Utils;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class NameSearchUtil
{
    public function nameSearching($keyword)
    {
        $namedProducts = '';
        $namedProducts = DB::table('product_branches')
            ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('taxes', 'products.tax_id', 'taxes.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            ->select(
                'products.id',
                'products.name',
                'products.product_code',
                'products.is_combo',
                'products.is_manage_stock',
                'products.is_purchased',
                'products.is_show_emi_on_pos',
                'products.is_variant',
                'products.product_cost',
                'products.product_cost_with_tax',
                'products.product_price',
                'products.profit',
                'products.quantity',
                'products.tax_id',
                'products.tax_type',
                'products.thumbnail_photo',
                'products.type',
                'products.unit_id',
                'taxes.id as tax_id',
                'taxes.tax_name',
                'taxes.tax_percent',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'units.id as unit_id',
                'units.name as unit_name',
            )
            ->where('products.is_for_sale', 1)
            ->where('products.status', 1)
            ->where('product_branches.branch_id', auth()->user()->branch_id)
            ->where('products.name', 'LIKE',  $keyword . '%')->orderBy('id', 'desc')->limit(25)
            ->get();

        // $namedProducts = '';
        // $namedProducts = Product::with([
        //     'product_variants:id,product_id,variant_name,variant_code,variant_cost,variant_cost_with_tax,variant_price',
        //     'product_variants.updateVariantCost',
        //     'tax:id,tax_name,tax_percent',
        //     'unit:id,name',
        //     'updateProductCost',
        // ])
        //     ->where('name', 'LIKE',  $keyword . '%')
        //     ->where('is_for_sale', 1)
        //     ->where('status', 1)->select(
        //         'id',
        //         'name',
        //         'product_code',
        //         'is_combo',
        //         'is_manage_stock',
        //         'is_purchased',
        //         'is_show_emi_on_pos',
        //         'is_variant',
        //         'product_cost',
        //         'product_cost_with_tax',
        //         'product_price',
        //         'profit',
        //         'quantity',
        //         'tax_id',
        //         'tax_type',
        //         'thumbnail_photo',
        //         'type',
        //         'unit_id',
        //     )->orderBy('id', 'desc')->limit(25)->get();

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
