<?php

namespace App\Services\GeneralSearch;

use App\Models\Product;
use App\Models\Products\ProductVariant;
use Illuminate\Support\Facades\DB;

class GeneralProductSearchService
{
    public function getProductByKeyword($product, $keyWord, $priceGroupId, $isShowNotForSaleItem, $branchId = null)
    {
        if ($product) {

            if ($isShowNotForSaleItem == 0 && $product->is_for_sale == 0) {

                return response()->json(['errorMsg' => __("Product is not for sale")]);
            }

            if ($product->type == 2) {

                return response()->json(['errorMsg' => __("Combo Product is not sellable in this demo")]);
            } else {

                return response()->json(
                    [
                        'product' => $product,
                        'discount' => $this->productDiscount($product->id, $priceGroupId, $product->brand_id, $product->category_id),
                    ]
                );
            }
        } else {

            $variantProduct = ProductVariant::with(
                'product',
                'product.productAccessBranch',
                'updateVariantCost',
                'product.tax:id,name,tax_percent',
                'product.unit:id,name,code_name',
                'product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
                'variantBranchStock',
            )->where('variant_code', $keyWord)
                ->select([
                    'id',
                    'product_id',
                    'variant_name',
                    'variant_code',
                    'variant_quantity',
                    'variant_cost',
                    'variant_cost_with_tax',
                    'variant_profit',
                    'variant_price',
                ])->first();

            if ($variantProduct && $variantProduct?->product?->productAccessBranch($branchId)) {

                if ($isShowNotForSaleItem == 0 && $variantProduct->product->is_for_sale == 0) {

                    return response()->json(['errorMsg' => __("Product is not for sale")]);
                }

                return response()->json(
                    [
                        'variant_product' => $variantProduct,
                        'discount' => $this->productDiscount($variantProduct->product_id, $priceGroupId, $variantProduct?->product?->brand_id, $variantProduct?->product?->category_id),
                    ]
                );
            }
        }

        return $this->nameSearching($keyWord, $isShowNotForSaleItem, $branchId);
    }

    private function productDiscount($productId, $priceGroupId, $brandId, $categoryId)
    {
        $presentDate = date('Y-m-d');
        $__priceGroupId = $priceGroupId && $priceGroupId != 'no_id' ? $priceGroupId : null;
        $__categoryId = $categoryId ? $categoryId : null;
        $__brandId = $brandId ? $brandId : null;

        $discountProductWise = DB::table('discount_products')
            ->where('discount_products.product_id', $productId)
            ->leftJoin('discounts', 'discount_products.discount_id', 'discounts.id')
            ->where('discounts.is_active', 1)
            ->where('discounts.price_group_id', $__priceGroupId)
            ->whereRaw('"' . $presentDate . '" between `start_at` and `end_at`')
            ->orderBy('discounts.priority', 'desc')
            ->first();

        if ($discountProductWise) {

            return $this->setDiscount($discountProductWise);
        }

        $discountBrandCategoryWise = '';
        $discountBrandCategoryWiseQ = DB::table('discounts')
            ->where('discounts.is_active', 1)
            //->where('discounts.price_group_id', $__priceGroupId)
            ->whereRaw('"' . $presentDate . '" between `start_at` and `end_at`');

        if ($__brandId && $__categoryId) {

            $discountBrandCategoryWiseQ->where('discounts.category_id', '!=', null);
            $discountBrandCategoryWiseQ->where('discounts.brand_id', '!=', null);
            $discountBrandCategoryWiseQ->where('discounts.category_id', $__categoryId);
            $discountBrandCategoryWiseQ->where('discounts.brand_id', $__categoryId);
        } elseif ($__brandId && !$__categoryId) {

            $discountBrandCategoryWiseQ->where('discounts.category_id', null);
            $discountBrandCategoryWiseQ->where('discounts.brand_id', '!=', null);
            $discountBrandCategoryWiseQ->where('discounts.brand_id', $__brandId);
        } elseif (!$__brandId && $__categoryId) {

            $discountBrandCategoryWiseQ->where('discounts.brand_id', null);
            $discountBrandCategoryWiseQ->where('discounts.category_id', '!=', null);
            $discountBrandCategoryWiseQ->where('discounts.category_id', $__categoryId);
        }

        $discountBrandCategoryWise = $discountBrandCategoryWiseQ
            ->select('discounts.discount_type', 'discounts.discount_amount', 'discounts.apply_in_customer_group')
            ->orderBy('discounts.priority', 'desc')
            ->first();

        return $this->setDiscount($discountBrandCategoryWise);

        // if (!$discountBrandCategoryWise) {

        //     return $this->setDiscount(NULL);
        // }

        // if ($discountBrandCategoryWise->brand_id && $discountBrandCategoryWise->category_id) {

        //     if ($discountBrandCategoryWise->brand_id == $__brand_id && $discountBrandCategoryWise->category_id == $__category_id) {

        //         return $this->setDiscount($discountBrandCategoryWise);
        //     }
        // } elseif (!$discountBrandCategoryWise->brand_id && $discountBrandCategoryWise->category_id) {

        //     if ($discountBrandCategoryWise->category_id == $__category_id) {

        //         return $this->setDiscount($discountBrandCategoryWise);
        //     }
        // } elseif ($discountBrandCategoryWise->brand_id && !$discountBrandCategoryWise->category_id) {

        //     if ($discountBrandCategoryWise->brand_id == $__brand_id) {

        //         return $this->setDiscount($discountBrandCategoryWise);
        //     }
        // }
    }

    private function setDiscount($discount)
    {
        $discountDetails = [];
        $discountDetails['discount_type'] = isset($discount->discount_type) ? $discount->discount_type : 1;
        $discountDetails['discount_amount'] = isset($discount->discount_amount) ? $discount->discount_amount : 0;
        //$discountDetails['apply_in_customer_group'] = isset($discount->apply_in_customer_group) ? $discount->apply_in_customer_group : 0;

        return $discountDetails;
    }

    public function getProductDiscountById($productId, $priceGroupId = null)
    {
        $product = Product::with(['unit:id,name,code_name', 'unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier'])
            ->where('id', $productId)->select('id', 'is_manage_stock', 'unit_id', 'brand_id', 'category_id', 'quantity')->first();

        return response()->json([
            'discount' => $this->productDiscount($product->id, $priceGroupId, $product->brand_id, $product->category_id),
            'unit' => $product?->unit,
        ]);
    }

    public function getProductDiscountByIdWithAvailableStock($productId, $variantId, $priceGroupId)
    {
        $product = Product::with(['unit:id,name,code_name', 'unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier'])
            ->where('id', $productId)
            ->select('id', 'is_manage_stock', 'unit_id', 'brand_id', 'category_id', 'quantity')->first();

        return response()->json([
            'discount' => $this->productDiscount($productId, $priceGroupId, $product->brand_id, $product->category_id),
            'stock' => $this->getAvailableStock($productId, $variantId),
            'unit' => $product?->unit,
        ]);
    }

    public function singleProductBranchStock($productId, $branchId)
    {
        $product = DB::table('products')->where('id', $productId)
            ->select('id', 'is_manage_stock', 'quantity')->first();

        if ($product->is_manage_stock == 0) {

            return response()->json(['stock' => PHP_INT_MAX]);
        }

        $productStock = DB::table('product_stocks')
            ->where('product_id', $productId)->where('branch_id', $branchId)->where('warehouse_id', null)
            ->first();

        if ($productStock) {

            return response()->json(['stock' => $productStock->stock, 'all_stock' => $product->quantity]);
        } else {

            return response()->json(['errorMsg' => 'This product is not available in this Shop/Business.']);
        }
    }

    public function singleProductWarehouseStock($productId, $warehouseId)
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock', 'quantity')
            ->first();

        if ($product->is_manage_stock == 0) {

            return response()->json(['stock' => PHP_INT_MAX]);
        }

        $productStock = DB::table('product_stocks')->where('product_id', $productId)->where('warehouse_id', $warehouseId)->first();

        if ($productStock) {

            return response()->json(['stock' => $productStock->stock, 'all_stock' => $product->quantity]);
        } else {

            return response()->json(['errorMsg' => 'The Product is not available in selected warehouse.']);
        }
    }

    public function variantProductBranchStock($productId, $variantId, $branchId)
    {
        $product = DB::table('products')
            ->where('id', $productId)
            ->select('id', 'is_manage_stock', 'brand_id', 'category_id', 'quantity')
            ->first();

        if ($product->is_manage_stock == 0) {

            return response()->json(['stock' => PHP_INT_MAX]);
        }

        $productStock = DB::table('product_stocks')->where('product_id', $productId)
        ->where('variant_id', $variantId)->where('branch_id', $branchId)->where('warehouse_id', null)
        ->first();

        if ($productStock) {

            return response()->json(['stock' => $productStock->stock, 'all_stock' => $product->quantity]);
        } else {

            return response()->json(['errorMsg' => 'This variant is not available in this Shop/Business.']);
        }
    }

    public function variantProductWarehouseStock($productId, $variantId, $warehouseId)
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock', 'quantity')
            ->first();

        if ($product->is_manage_stock == 0) {

            return response()->json(['stock' => PHP_INT_MAX]);
        }

        $productStock = DB::table('product_stocks')->where('warehouse_id', $warehouseId)->where('product_id', $productId)->first();

        if ($productStock) {

            return response()->json(['stock' => $productStock->stock, 'all_stock' => $product->quantity]);
        } else {

            return response()->json(['errorMsg' => 'This variant is not available in the selected warehouse..']);
        }

    }

    public function getAvailableStock($productId, $variantId)
    {
        $variantId = $variantId != 'noid' ? $variantId : null;

        $stock = 0;
        if ($variantId) {

            $branchVariant = DB::table('product_branch_variants')
                ->leftJoin('product_branches', 'product_branch_variants.product_branch_id', 'product_branches.id')
                ->where('product_branch_variants.product_variant_id', $variantId)
                ->select('variant_quantity')->first();

            $stock += $branchVariant ? $branchVariant->variant_quantity : 0;

            $warehouseVariant = DB::table('product_warehouse_variants')
                ->leftJoin('product_warehouses', 'product_warehouse_variants.product_warehouse_id', 'product_warehouses.id')
                ->leftJoin('warehouses', 'product_warehouses.warehouse_id', 'warehouses.id')
                ->where('product_warehouse_variants.product_variant_id', $variantId)
                ->select(DB::raw('IFNULL(SUM(product_warehouse_variants.variant_quantity), 0) as variant_quantity'))
                ->groupBy('product_warehouse_variants.product_variant_id')->get();

            $stock += $warehouseVariant->sum('variant_quantity');
        } else {

            $branchProduct = DB::table('product_branches')
                ->where('product_branches.product_id', $productId)->select('product_quantity')->first();

            $stock += $branchProduct ? $branchProduct->product_quantity : 0;

            $warehouseVariant = DB::table('product_warehouses')
                ->leftJoin('warehouses', 'product_warehouses.warehouse_id', 'warehouses.id')
                ->where('product_warehouses.product_id', $productId)
                ->select(DB::raw('IFNULL(SUM(product_warehouses.product_quantity), 0) as product_quantity'))
                ->groupBy('product_warehouses.product_id')->get();

            $stock += $warehouseVariant->sum('product_quantity');
        }

        return $stock;
    }

    public function nameSearching($keyword, $isShowNotForSaleItem = 1, $branchId = null)
    {
        $namedProducts = '';

        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $query = DB::table('products')
            ->where('products.status', 1)
            ->where('product_access_branches.branch_id', $ownBranchIdOrParentBranchId)
            ->where('products.name', 'LIKE', '%' . $keyword . '%');

        if ($isShowNotForSaleItem == 0) {

            $query->where('is_for_sale', 1);
        }

        $query->orWhere('product_variants.variant_name', 'LIKE', '%' . $keyword . '%');

        $namedProducts = $query->leftJoin('accounts as taxes', 'products.tax_ac_id', 'taxes.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('product_access_branches', 'products.id', 'product_access_branches.product_id')
            ->select(
                // 'product_branches.product_quantity as p_qty',
                // 'product_branch_variants.variant_quantity as v_qty',
                'products.id',
                'products.name',
                'products.product_code',
                'products.is_combo',
                'products.is_manage_stock',
                // 'products.is_purchased',
                'products.is_show_emi_on_pos',
                'products.has_batch_no_expire_date',
                'products.is_variant',
                'products.product_cost',
                'products.product_cost_with_tax',
                'products.product_price',
                'products.profit',
                'products.quantity',
                'products.tax_ac_id',
                'products.tax_type',
                'products.thumbnail_photo',
                'products.type',
                'products.unit_id',
                'taxes.name as tax_name',
                'taxes.tax_percent',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_profit',
                'product_variants.variant_price',
                'product_variants.variant_quantity',
                'units.name as unit_name',
            )
            // ->where('products.name', 'LIKE',  $keyword . '%')->orderBy('id', 'desc')->limit(25)
            ->orderBy('products.name', 'asc')->limit(25)
            ->get();

        if ($namedProducts && count($namedProducts) > 0) {

            return response()->json(['namedProducts' => $namedProducts]);
        } else {

            return response()->json(['NotFoundMsg' => 'Not Found.']);
        }
    }

    public function getProductUnitAndMultiplierUnit($productId)
    {
        $product = Product::with(['unit:id,name,code_name', 'unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier'])
            ->where('id', $productId)->select('id', 'unit_id')->first();

        return $product?->unit;
    }
}
