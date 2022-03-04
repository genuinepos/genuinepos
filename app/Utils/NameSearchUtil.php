<?php

namespace App\Utils;

use App\Models\Product;
use App\Models\ProductVariant;
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
                'taxes.tax_name',
                'taxes.tax_percent',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
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

            $productBranchVariant = DB::table('product_branch_variants')
                ->where('product_branch_id', $productBranch->id)
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


    public function checkWarehouseSingleProduct($product_id, $warehouse_id)
    {
        $productWarehouse = DB::table('product_warehouses')->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->first();

        if ($productWarehouse) {

            if ($productWarehouse->product_quantity > 0) {

                return response()->json($productWarehouse->product_quantity);
            } else {

                return response()->json(['errorMsg' => 'Stock is out of this product from this warehouse']);
            }
        } else {

            return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
        }
    }

    // Check warehouse product variant qty 
    public function checkWarehouseProductVariant($product_id, $variant_id, $warehouse_id)
    {
        $productWarehouse = DB::table('product_warehouses')
            ->where('warehouse_id', $warehouse_id)
            ->where('product_id', $product_id)
            ->first();

        if (is_null($productWarehouse)) {

            return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
        }

        $productWarehouseVariant = DB::table('product_warehouse_variants')
            ->where('product_warehouse_id', $productWarehouse->id)
            ->where('product_id', $product_id)
            ->where('product_variant_id', $variant_id)
            ->first();

        if (is_null($productWarehouseVariant)) {

            return response()->json(['errorMsg' => 'This variant is not available in this warehouse.']);
        }

        if ($productWarehouse && $productWarehouseVariant) {

            if ($productWarehouseVariant->variant_quantity > 0) {

                return response()->json($productWarehouseVariant->variant_quantity);
            } else {

                return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this warehouse.']);
            }
        } else {

            return response()->json(['errorMsg' => 'This variant is not available in this shop.']);
        }
    }

    public function searchStockToBranch($product, $product_code, $branch_id)
    {
        if ($product) {

            $productBranch = DB::table('product_branches')
                ->where('branch_id', $branch_id)
                ->where('product_id', $product->id)
                ->select('product_quantity')
                ->first();

            if ($productBranch) {

                if ($product->type == 2) {

                    return response()->json(['errorMsg' => 'Combo product is not transferable.']);
                } else {

                    if ($productBranch->product_quantity > 0) {

                        return response()->json(
                            [
                                'product' => $product,
                                'qty_limit' => $productBranch->product_quantity
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this product in this Business Location/Shop.']);
                    }
                }
            } else {

                return response()->json(['errorMsg' => 'This product is not available in this Business Location/Shop.']);
            }
        } else {

            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')
                ->where('variant_code', $product_code)
                ->select([
                    'id', 'product_id', 'variant_name', 'variant_code', 'variant_quantity', 'variant_cost', 'variant_cost_with_tax', 'variant_profit', 'variant_price'
                ])->first();

            if ($variant_product) {

                if ($variant_product) {

                    $productBranch = DB::table('product_branches')
                        ->where('branch_id', $branch_id)
                        ->where('product_id', $variant_product->product_id)
                        ->first();

                    if (is_null($productBranch)) {

                        return response()->json(['errorMsg' => 'This product is not available in this Business Location/Shop.']);
                    }

                    $productBranchVariant = DB::table('product_branch_variants')
                        ->where('product_branch_id', $productBranch->id)
                        ->where('product_id', $variant_product->product_id)
                        ->where('product_variant_id', $variant_product->id)
                        ->select('variant_quantity')
                        ->first();

                    if (is_null($productBranchVariant)) {

                        return response()->json(['errorMsg' => 'This variant is not available in this Business Location/Shop.']);
                    }

                    if ($productBranch && $productBranchVariant) {

                        if ($productBranchVariant->variant_quantity > 0) {

                            return response()->json([
                                'variant_product' => $variant_product,
                                'qty_limit' => $productBranchVariant->variant_quantity
                            ]);
                        } else {

                            return response()->json(['errorMsg' => 'Stock is out of this product(variant) from this Business Location/Shop.']);
                        }
                    } else {

                        return response()->json(['errorMsg' => 'This product is not available in this Business Location/Shop.']);
                    }
                }
            }
        }

        return $this->nameSearching($product_code);
    }

    public function searchStockToWarehouse($product, $product_code, $warehouse_id)
    {
        if ($product) {

            $productWarehouse = DB::table('product_warehouses')->where('warehouse_id', $warehouse_id)
                ->where('product_id', $product->id)
                ->first();

            if ($productWarehouse) {

                if ($product->type == 2) {

                    return response()->json(['errorMsg' => 'Combo product is not transferable.']);
                } else {

                    if ($productWarehouse->product_quantity > 0) {

                        return response()->json(
                            [
                                'product' => $product,
                                'qty_limit' => $productWarehouse->product_quantity
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this product of this warehouse']);
                    }
                }
            } else {

                return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
            }
        } else {

            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')
                ->where('variant_code', $product_code)
                ->first();

            if ($variant_product) {

                $productWarehouse = DB::table('product_warehouses')
                    ->where('warehouse_id', $warehouse_id)
                    ->where('product_id', $variant_product->product_id)
                    ->first();

                if (is_null($productWarehouse)) {

                    return response()->json(['errorMsg' => 'This product is not available in this warehouse']);
                }

                $productWarehouseVariant = DB::table('product_warehouse_variants')
                    ->where('product_warehouse_id', $productWarehouse->id)
                    ->where('product_id', $variant_product->product_id)
                    ->where('product_variant_id', $variant_product->id)
                    ->first();

                if (is_null($productWarehouseVariant)) {

                    return response()->json(['errorMsg' => 'This variant is not available in this warehouse']);
                }

                if ($productWarehouse && $productWarehouseVariant) {

                    if ($productWarehouseVariant->variant_quantity > 0) {

                        return response()->json(
                            [
                                'variant_product' => $variant_product,
                                'qty_limit' => $productWarehouseVariant->variant_quantity
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this warehouse']);
                    }
                } else {

                    return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
                }
            }
        }

        return $this->nameSearching($product_code);
    }
}