<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Models\Sales\Sale;
use Illuminate\Support\Facades\DB;

class SalesHelperService
{
    public function getPosSelectableProducts($request): ?object
    {
        $generalSettings = config('generalSettings');

        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
        $products = '';

        $query = DB::table('products')
            ->where('products.is_for_sale', 1)
            ->where('product_access_branches.branch_id', $ownBranchIdOrParentBranchId)
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('product_access_branches', 'products.id', 'product_access_branches.product_id')
            ->leftJoin('accounts as tax', 'products.tax_ac_id', 'tax.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('purchase_products as updateProductCost', function ($join) use ($generalSettings) {

                $stockAccountingMethod = $generalSettings['business__stock_accounting_method'];

                if ($stockAccountingMethod == 1) {

                    $ordering = 'asc';
                } else {

                    $ordering = 'desc';
                }

                $join->on('products.id', 'updateProductCost.product_id')
                    ->where('updateProductCost.left_qty', '>', '0')
                    ->where('updateProductCost.variant_id', null)
                    ->where('updateProductCost.branch_id', auth()->user()->branch_id)
                    ->orderBy('updateProductCost.created_at', $ordering)->select('updateProductCost.product_id', 'updateProductCost.net_unit_cost')->limit(1);
                // ->whereRaw('orders.id = (SELECT MAX(id) FROM orders WHERE user_id = users.id)');
            })->leftJoin('purchase_products as updateVariantCost', function ($join) use ($generalSettings) {

                $stockAccountingMethod = $generalSettings['business__stock_accounting_method'];

                if ($stockAccountingMethod == 1) {

                    $ordering = 'asc';
                } else {

                    $ordering = 'desc';
                }

                $join->on('product_variants.id', 'updateVariantCost.variant_id')
                    ->where('updateVariantCost.left_qty', '>', '0')
                    ->where('updateVariantCost.branch_id', auth()->user()->branch_id)
                    ->orderBy('updateVariantCost.created_at', $ordering)->select('updateVariantCost.product_id', 'updateVariantCost.net_unit_cost')->limit(1);
                // ->whereRaw('orders.id = (SELECT MAX(id) FROM orders WHERE user_id = users.id)');
            });

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->brand_id) {

            $query->where('products.brand_id', $request->brand_id);
        }

        $query->select(
            [
                'products.id as product_id',
                'products.name as product_name',
                'products.product_code',
                'products.status',
                'products.is_variant',
                'products.type',
                'products.tax_type',
                'products.product_cost_with_tax',
                'products.product_price',
                'products.is_manage_stock',
                'products.thumbnail_photo',
                'products.is_combo',
                'products.quantity',
                'products.is_show_emi_on_pos',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_variants.variant_image',
                'units.id as unit_id',
                'units.name as unit_name',
                'tax.id as tax_ac_id',
                'tax.tax_percent',
                'updateProductCost.net_unit_cost as update_product_cost',
                'updateVariantCost.net_unit_cost as update_variant_cost',
            ]
        )->distinct('product_access_branches.branch_id');

        if (!$request->category_id && !$request->brand_id) {

            $products = $query->orderBy('products.id', 'desc')->limit(90)->get();
        } else {

            $products = $query->orderBy('products.id', 'desc')->get();
        }

        return $products;
    }
}