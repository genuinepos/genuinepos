<?php

namespace App\Utils;

use App\Models\Product;
use App\Models\ProductBranch;
use App\Models\ProductWarehouse;
use App\Models\ProductBranchVariant;
use App\Models\ProductVariant;
use App\Models\ProductWarehouseVariant;

class PurchaseUtil
{
    public function updateStockForPurchaseStore($request)
    {
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $quantities = $request->quantities;

        if (isset($request->warehouse_id)) {
            $index2 = 0;
            foreach ($product_ids as $productId) {
                // add warehouse product
                $productWarehouse = ProductWarehouse::where('warehouse_id', $request->warehouse_id)->where('product_id', $productId)->first();
                if ($productWarehouse) {
                    $productWarehouse->product_quantity += (float)$quantities[$index2];
                    $productWarehouse->save();
                    if ($variant_ids[$index2] != 'noid') {
                        // add warehouse product variant 
                        $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                        if ($productWarehouseVariant) {
                            $productWarehouseVariant->variant_quantity += (float)$quantities[$index2];
                            $productWarehouseVariant->save();
                        } else {
                            $addProductWarehouseVariant = new ProductWarehouseVariant();
                            $addProductWarehouseVariant->product_warehouse_id = $productWarehouse->id;
                            $addProductWarehouseVariant->product_id = $productId;
                            $addProductWarehouseVariant->product_variant_id = $variant_ids[$index2];
                            $addProductWarehouseVariant->variant_quantity = $quantities[$index2];
                            $addProductWarehouseVariant->save();
                        }
                    }
                } else {
                    $addProductWarehouse = new ProductWarehouse();
                    $addProductWarehouse->warehouse_id = $request->warehouse_id;
                    $addProductWarehouse->product_id = $productId;
                    $addProductWarehouse->product_quantity = $quantities[$index2];
                    $addProductWarehouse->save();
                    if ($variant_ids[$index2] != 'noid') {
                        // add warehouse product variant 
                        $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $addProductWarehouse->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                        if ($productWarehouseVariant) {
                            $productWarehouseVariant->variant_quantity += (float)$quantities[$index2];
                            $productWarehouseVariant->save();
                        } else {
                            $addProductWarehouseVariant = new ProductWarehouseVariant();
                            $addProductWarehouseVariant->product_warehouse_id = $addProductWarehouse->id;
                            $addProductWarehouseVariant->product_id = $productId;
                            $addProductWarehouseVariant->product_variant_id = $variant_ids[$index2];
                            $addProductWarehouseVariant->variant_quantity = $quantities[$index2];
                            $addProductWarehouseVariant->save();
                        }
                    }
                }
                $index2++;
            }
        } else {
            $index2 = 0;
            if (auth()->user()->branch_id) {
                foreach ($product_ids as $productId) {
                    // add branch product
                    $productBranch = ProductBranch::where('branch_id', auth()->user()->branch_id)->where('product_id', $productId)->first();
                    if ($productBranch) {
                        $productBranch->product_quantity = $productBranch->product_quantity + $quantities[$index2];
                        $productBranch->save();
                        if ($variant_ids[$index2] != 'noid') {
                            // add product branch variant 
                            $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                            if ($productBranchVariant) {
                                $productBranchVariant->variant_quantity += (float)$quantities[$index2];
                                $productBranchVariant->save();
                            } else {
                                $addProductBranchVariant = new ProductBranchVariant();
                                $addProductBranchVariant->product_branch_id = $productBranch->id;
                                $addProductBranchVariant->product_id = $productId;
                                $addProductBranchVariant->product_variant_id = $variant_ids[$index2];
                                $addProductBranchVariant->variant_quantity = $quantities[$index2];
                                $addProductBranchVariant->save();
                            }
                        }
                    } else {
                        $addProductBranch = new ProductBranch();
                        $addProductBranch->branch_id = auth()->user()->branch_id;
                        $addProductBranch->product_id = $productId;
                        $addProductBranch->product_quantity = $quantities[$index2];
                        $addProductBranch->save();
                        if ($variant_ids[$index2] != 'noid') {
                            // add product branch variant 
                            $productBranchVariant = ProductBranchVariant::where('product_branch_id', $addProductBranch->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                            if ($productBranchVariant) {
                                $productBranchVariant->variant_quantity += (float)$quantities[$index2];
                                $productBranchVariant->save();
                            } else {
                                $addProductBranchVariant = new ProductWarehouseVariant();
                                $addProductBranchVariant->product_branch_id = $addProductBranch->id;
                                $addProductBranchVariant->product_id = $productId;
                                $addProductBranchVariant->product_variant_id = $variant_ids[$index2];
                                $addProductBranchVariant->variant_quantity = $quantities[$index2];
                                $addProductBranchVariant->save();
                            }
                        }
                    }
                    $index2++;
                }
            } else {
                $index2 = 0;
                foreach ($product_ids as $productId) {
                    $updateMbStock = Product::where('id', $productId)->first();
                    $updateMbStock->mb_stock += (float)$quantities[$index2];
                    $updateMbStock->save();

                    if ($variant_ids[$index2] != 'noid') {
                        $updateProVariantMbStock = ProductVariant::where('id', $variant_ids[$index2])
                            ->where('product_id', $productId)->first();
                        $updateProVariantMbStock->mb_stock += (float)$quantities[$index2];
                        $updateProVariantMbStock->save();
                    }
                }
            }
        }
    }
}