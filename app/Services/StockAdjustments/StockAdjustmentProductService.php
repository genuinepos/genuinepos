<?php

namespace App\Services\StockAdjustments;

use App\Models\StockAdjustments\StockAdjustmentProduct;

class StockAdjustmentProductService
{
    public function addStockAdjustmentProduct(object $request, int $stockAdjustmentId, int $index): object
    {
        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $warehouseId = $request->warehouse_ids[$index] != 'noid' ? $request->warehouse_ids[$index] : null;

        $addStockAdjustmentProduct = new StockAdjustmentProduct();
        $addStockAdjustmentProduct->stock_adjustment_id = $stockAdjustmentId;
        $addStockAdjustmentProduct->branch_id = auth()->user()->branch_id;
        $addStockAdjustmentProduct->warehouse_id = $warehouseId;
        $addStockAdjustmentProduct->product_id = $request->product_ids[$index];
        $addStockAdjustmentProduct->variant_id = $variantId;
        $addStockAdjustmentProduct->quantity = $request->quantities[$index];
        $addStockAdjustmentProduct->unit_id = $request->unit_ids[$index];
        $addStockAdjustmentProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addStockAdjustmentProduct->subtotal = $request->subtotals[$index];
        $addStockAdjustmentProduct->save();

        return $addStockAdjustmentProduct;
    }
}
