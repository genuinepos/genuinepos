<?php

namespace App\Services\GeneralSearch;

use App\Enums\BooleanType;
use App\Models\Product;
use App\Models\Products\ProductVariant;
use Illuminate\Support\Facades\DB;

class GeneralProductSearchService
{
    public function getProductByKeyword($product, $keyWord, $priceGroupId, $isShowNotForSaleItem, $branchId = null)
    {
        if ($product) {

            if ($isShowNotForSaleItem == 0 && $product->is_for_sale == 0) {

                return response()->json(['errorMsg' => __('Product is not for sale')]);
            }

            if ($product->type == 2) {

                return response()->json(['errorMsg' => __('Combo Product is not sellable in this demo')]);
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
                'product.tax:id,tax_percent',
                'product.unit:id,name',
                'product.unit.childUnits:id,name,base_unit_id,base_unit_multiplier',
                'variantBranchStock',
            )->where('variant_code', $keyWord)
                ->select([
                    'id',
                    'product_id',
                    'variant_name',
                    'variant_code',
                    'variant_cost',
                    'variant_cost_with_tax',
                    'variant_profit',
                    'variant_price',
                ])->first();

            if ($variantProduct && $variantProduct?->product?->productAccessBranch($branchId)) {

                if ($isShowNotForSaleItem == 0 && $variantProduct?->product?->is_for_sale == 0) {

                    return response()->json(['errorMsg' => __('Product is not for sale')]);
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
            ->whereRaw('"'.$presentDate.'" between `start_at` and `end_at`')
            ->orderBy('discounts.priority', 'desc')
            ->first();

        if ($discountProductWise) {

            return $this->setDiscount($discountProductWise);
        }

        $discountBrandCategoryWise = '';
        if (isset($__brandId) || isset($__categoryId)) {

            $discountBrandCategoryWiseQ = DB::table('discounts')
                ->where('discounts.is_active', 1)
                //->where('discounts.price_group_id', $__priceGroupId)
                ->whereRaw('"'.$presentDate.'" between `start_at` and `end_at`');

            if (isset($__brandId) && isset($__categoryId)) {

                $discountBrandCategoryWiseQ->where('discounts.category_id', '!=', null);
                $discountBrandCategoryWiseQ->where('discounts.brand_id', '!=', null);
                $discountBrandCategoryWiseQ->where('discounts.category_id', $__categoryId);
                $discountBrandCategoryWiseQ->where('discounts.brand_id', $__brandId);
            } elseif (isset($__brandId) && ! ($__categoryId)) {

                $discountBrandCategoryWiseQ->where('discounts.category_id', null);
                $discountBrandCategoryWiseQ->where('discounts.brand_id', '!=', null);
                $discountBrandCategoryWiseQ->where('discounts.brand_id', $__brandId);
            } elseif (! isset($__brandId) && isset($__categoryId)) {

                $discountBrandCategoryWiseQ->where('discounts.brand_id', null);
                $discountBrandCategoryWiseQ->where('discounts.category_id', '!=', null);
                $discountBrandCategoryWiseQ->where('discounts.category_id', $__categoryId);
            }

            $discountBrandCategoryWise = $discountBrandCategoryWiseQ
                ->select('discounts.discount_type', 'discounts.discount_amount', 'discounts.apply_in_customer_group')
                ->orderBy('discounts.priority', 'desc')
                ->first();
        }

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

    public function getProductDiscountByIdWithAvailableStock($productId, $variantId, $priceGroupId, $branchId)
    {
        $product = Product::with(['unit:id,name,code_name', 'unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier'])
            ->where('id', $productId)
            ->select('id', 'is_manage_stock', 'unit_id', 'brand_id', 'category_id', 'quantity')->first();

        return response()->json([
            'discount' => $this->productDiscount($productId, $priceGroupId, $product->brand_id, $product->category_id),
            'stock' => $this->getAvailableStock($productId, $variantId, $branchId),
            'unit' => $product?->unit,
        ]);
    }

    public function getProductDiscountByIdWithSingleOrVariantBranchStock($productId, $variantId, $priceGroupId, $branchId)
    {
        $__variantId = $variantId != 'noid' ? $variantId : null;
        $stock = 0;
        if ($__variantId) {

            $variantStock = DB::table('product_stocks')->where('product_id', $productId)
                ->where('variant_id', $variantId)->where('branch_id', $branchId)
                ->where('warehouse_id', null)->select('stock')->first();

            $stock = $variantStock ? $variantStock->stock : 0;
        } else {

            $productStock = DB::table('product_stocks')
                ->where('product_id', $productId)->where('branch_id', $branchId)
                ->where('warehouse_id', null)->select('stock')->first();

            $stock = $productStock ? $productStock->stock : 0;
        }

        $product = Product::with(['unit:id,name', 'unit.childUnits:id,name,base_unit_id,base_unit_multiplier'])
            ->where('id', $productId)
            ->select('id', 'is_manage_stock', 'unit_id', 'brand_id', 'category_id', 'quantity')->first();

        return response()->json([
            'discount' => $this->productDiscount($productId, $priceGroupId, $product->brand_id, $product->category_id),
            'stock' => $product->is_manage_stock == 1 ? $stock : PHP_INT_MAX,
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

            return response()->json(['errorMsg' => 'Product stock is not available in this Shop/Business.']);
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

            return response()->json(['errorMsg' => __('This variant is not available in this Shop/Business.')]);
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

            return response()->json(['errorMsg' => __('This variant is not available in the selected warehouse.')]);
        }
    }

    public function getAvailableStock($productId, $variantId, $branchId)
    {
        $variantId = $variantId != 'noid' ? $variantId : null;

        $stock = 0;
        if ($variantId) {

            $variantStock = DB::table('product_stocks')->where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->where('branch_id', $branchId)
                ->select(DB::raw('SUM(stock) as stock'))
                ->groupBy('branch_id', 'product_id', 'variant_id')
                ->get();

            $stock = $variantStock->sum('stock');
        } else {

            $productStock = DB::table('product_stocks')
                ->where('product_id', $productId)
                ->where('branch_id', $branchId)
                ->select(DB::raw('SUM(stock) as stock'))
                ->groupBy('branch_id', 'product_id', 'variant_id')
                ->get();

            $stock = $productStock->sum('stock');
        }

        return $stock;
    }

    public function nameSearching($keyword, $isShowNotForSaleItem = 1, $branchId = null)
    {
        $generalSettings = config('generalSettings');

        $namedProducts = '';

        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $query = DB::table('products')
            ->where('products.status', BooleanType::True->value)
            ->where('product_access_branches.branch_id', $ownBranchIdOrParentBranchId)
            ->where('products.name', 'LIKE', '%'.$keyword.'%');

        if ($isShowNotForSaleItem == BooleanType::False->value) {

            $query->where('is_for_sale', BooleanType::True->value);
        }

        $query->orWhere('product_variants.variant_name', 'LIKE', '%'.$keyword.'%');

        $query->leftJoin('accounts as taxes', 'products.tax_ac_id', 'taxes.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('product_access_branches', 'products.id', 'product_access_branches.product_id')
            ->select(
                'products.id',
                'products.name',
                'products.is_combo',
                'products.is_manage_stock',
                'products.is_show_emi_on_pos',
                'products.has_batch_no_expire_date',
                'products.is_variant',
                'products.product_cost',
                'products.product_cost_with_tax',
                'products.product_price',
                'products.profit',
                'products.tax_ac_id',
                'products.tax_type',
                'products.thumbnail_photo',
                'products.type',
                'products.unit_id',
                'taxes.tax_percent',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_profit',
                'product_variants.variant_price',
                'units.name as unit_name',
            )->distinct('product_access_branches.branch_id');
        // ->where('products.name', 'LIKE',  $keyword . '%')->orderBy('id', 'desc')->limit(25)

        $stockAccountingMethod = $generalSettings['business__stock_accounting_method'];

        if ($stockAccountingMethod == 1) {

            $ordering = 'asc';
        } else {

            $ordering = 'desc';
        }

        $namedProducts = $query->addSelect([
            DB::raw('(SELECT net_unit_cost FROM purchase_products WHERE product_id = products.id AND left_qty > 0 AND variant_id IS NULL AND branch_id '.(auth()->user()->branch_id ? '='.auth()->user()->branch_id : ' IS NULL').' ORDER BY created_at '.$ordering.' LIMIT 1) as update_product_cost'),
            DB::raw('(SELECT net_unit_cost FROM purchase_products WHERE variant_id = product_variants.id AND left_qty > 0 AND branch_id '.(auth()->user()->branch_id ? '='.auth()->user()->branch_id : ' IS NULL').' ORDER BY created_at '.$ordering.' LIMIT 1) as update_variant_cost'),
        ])->orderBy('products.name', 'asc')->limit(25)->get();

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
