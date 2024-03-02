<?php

namespace App\Services\Products;

use App\Enums\BooleanType;
use App\Models\Products\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Products\ProductStock;
use App\Models\Products\ProductVariant;

class ProductStockService
{
    public function adjustMainProductAndVariantStock(int $productId, int $variantId = null): void
    {
        $product = Product::where('id', $productId)->first();

        if ($product->is_manage_stock == BooleanType::True->value) {

            $productLedger = DB::table('product_ledgers')->where('product_ledgers.product_id', $productId)
                ->select(
                    DB::raw('SUM(product_ledgers.in) as stock_in'),
                    DB::raw('SUM(product_ledgers.out) as stock_out')
                )->groupBy('product_ledgers.product_id')->get();

            $productCurrentStock = $productLedger->sum('stock_in') - $productLedger->sum('stock_out');
            $product->quantity = $productCurrentStock;
            $product->save();

            if ($variantId) {

                $productLedger = DB::table('product_ledgers')->where('product_ledgers.product_id')
                    ->where('product_ledgers.variant_id', $variantId)
                    ->select(
                        DB::raw('SUM(product_ledgers.in) as stock_in'),
                        DB::raw('SUM(product_ledgers.out) as stock_out')
                    )->groupBy('product_ledgers.variant_id')->get();

                $variantCurrentStock = $productLedger->sum('stock_in') - $productLedger->sum('stock_out');
                $variant = ProductVariant::where('id', $variantId)->first();
                $variant->variant_quantity = $variantCurrentStock;
                $variant->save();
            }
        }
    }

    public function adjustBranchStock(int $productId, int $variantId = null, int $branchId = null): void
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock', 'product_cost')
            ->first();

        $this->addBranchProduct(productId: $productId, variantId: $variantId, branchId: $branchId);

        if ($product->is_manage_stock == BooleanType::True->value) {

            $productLedger = DB::table('product_ledgers')
                ->where('product_ledgers.product_id', $productId)
                ->where('product_ledgers.variant_id', $variantId)
                ->where('product_ledgers.branch_id', $branchId)
                ->where('product_ledgers.warehouse_id', null)
                ->select(
                    DB::raw('SUM(product_ledgers.in) as stock_in'),
                    DB::raw('SUM(product_ledgers.out) as stock_out'),
                    DB::raw('SUM(case when product_ledgers.in != 0 then product_ledgers.subtotal end) as total_purchased_cost'),
                )->groupBy('product_ledgers.product_id', 'product_ledgers.variant_id')->get();

            $currentStock = $productLedger->sum('stock_in') - $productLedger->sum('stock_out');

            $avgUnitCost = $currentStock > 0 ? $productLedger->sum('total_purchased_cost') / $currentStock : $product->product_cost;
            $stockValue = $avgUnitCost * $currentStock;

            $productStock = ProductStock::where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->where('branch_id', $branchId)
                ->where('warehouse_id', null)
                ->first();

            $productStock->stock = $currentStock;
            $productStock->stock_value = $stockValue;
            $productStock->save();
        }
    }

    public function adjustBranchAllStock(int $productId, int $variantId = null, int $branchId = null): void
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock', 'product_cost')
            ->first();

        $this->addBranchProduct(productId: $productId, variantId: $variantId, branchId: $branchId);

        if ($product->is_manage_stock == 1) {

            $productLedger = DB::table('product_ledgers')
                ->where('product_ledgers.product_id', $productId)
                ->where('product_ledgers.variant_id', $variantId)
                ->where('product_ledgers.branch_id', $branchId)
                ->select(
                    DB::raw('SUM(product_ledgers.in) as all_stock_in'),
                    DB::raw('SUM(product_ledgers.out) as all_stock_out'),
                )->groupBy('product_ledgers.product_id', 'product_ledgers.variant_id')->get();

            $allStock = $productLedger->sum('all_stock_in') - $productLedger->sum('all_stock_out');

            $productStock = ProductStock::where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->where('branch_id', $branchId)
                ->where('warehouse_id', null)
                ->first();

            $productStock->all_stock = $allStock;
            $productStock->save();
        }
    }

    public function adjustWarehouseStock(int $productId, int $variantId = null, int $warehouseId): void
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock', 'product_cost')
            ->first();

        $this->addWarehouseProduct(productId: $productId, variantId: $variantId, warehouseId: $warehouseId);

        if ($product->is_manage_stock == BooleanType::True->value) {

            $productLedger = DB::table('product_ledgers')
                ->where('product_ledgers.product_id', $productId)
                ->where('product_ledgers.variant_id', $variantId)
                ->where('product_ledgers.warehouse_id', $warehouseId)
                ->select(
                    DB::raw('SUM(product_ledgers.in) as stock_in'),
                    DB::raw('SUM(product_ledgers.out) as stock_out'),
                    DB::raw('SUM(case when product_ledgers.in != 0 then product_ledgers.subtotal end) as total_purchased_cost'),
                )->groupBy('product_ledgers.product_id', 'product_ledgers.variant_id')->get();

            $currentStock = $productLedger->sum('stock_in') - $productLedger->sum('stock_out');

            $avgUnitCost = $currentStock > 0 ? $productLedger->sum('total_purchased_cost') / $currentStock : $product->product_cost;
            $stockValue = $avgUnitCost * $currentStock;

            $productStock = ProductStock::where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->where('warehouse_id', $warehouseId)->first();

            $productStock->stock = $currentStock;
            $productStock->stock_value = $stockValue;
            $productStock->save();
        }
    }

    public function addWarehouseProduct(int $productId, int $variantId = null, int $warehouseId): void
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock')
            ->first();

        if ($product->is_manage_stock == 1) {

            $productStock = ProductStock::where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->where('warehouse_id', $warehouseId)->first();

            if (! $productStock) {

                $addProductStock = new ProductStock();
                $addProductStock->product_id = $productId;
                $addProductStock->variant_id = $variantId;
                $addProductStock->warehouse_id = $warehouseId;
                $addProductStock->branch_id = auth()->user()->branch_id;
                $addProductStock->save();
            }
        }
    }

    public function addBranchProduct(int $productId, int $variantId = null, int $branchId = null): void
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock')
            ->first();

        if ($product->is_manage_stock == BooleanType::True->value) {

            $productStock = ProductStock::where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->where('branch_id', $branchId)
                ->where('warehouse_id', null)->first();

            if (! $productStock) {

                $addProductStock = new ProductStock();
                $addProductStock->product_id = $productId;
                $addProductStock->variant_id = $variantId;
                $addProductStock->branch_id = $branchId;
                $addProductStock->save();
            }
        }
    }

    public function getAllStockUnderTheBranch(int $productId, int $variantId = null, int $branchId = null): float
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock')
            ->first();

        $productStock = ProductStock::where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->where('branch_id', $branchId)
            ->where('warehouse_id', null)->first();
    }
}
