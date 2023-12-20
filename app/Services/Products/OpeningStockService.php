<?php

namespace App\Services\Products;

use App\Models\Products\ProductOpeningStock;

class OpeningStockService
{
    public function addOrEditProductOpeningStock(object $request, int $index, int $productId = null, int $variantId = null): object
    {
        $generalSettings = config('generalSettings');
        $accountStartDate = $generalSettings['business__account_start_date'];
        $date = $accountStartDate;

        $branchId = isset($request->branch_ids[$index]) ? $request->branch_ids[$index] : null;
        $warehouseId = isset($request->warehouse_ids[$index]) ? $request->warehouse_ids[$index] : null;
        $productId = isset($request->product_ids[$index]) ? $request->product_ids[$index] : $productId;
        $variantId = isset($request->variant_ids[$index]) ? $request->variant_ids[$index] : $variantId;

        $addOrEditOpeningStock = '';
        $openingStock = ProductOpeningStock::where('branch_id', $branchId)
            ->where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)->first();

        if ($openingStock) {

            $addOrEditOpeningStock = $openingStock;
            $date = $openingStock->date;
        } else {

            $addOrEditOpeningStock = new ProductOpeningStock();
        }

        $addOrEditOpeningStock->branch_id = $branchId;
        $addOrEditOpeningStock->warehouse_id = $warehouseId;
        $addOrEditOpeningStock->product_id = $productId;
        $addOrEditOpeningStock->variant_id = $variantId;
        $addOrEditOpeningStock->quantity = isset($request->quantities[$index]) ? $request->quantities[$index] : 0;
        $addOrEditOpeningStock->unit_cost_inc_tax = isset($request->unit_costs_inc_tax[$index]) ? $request->unit_costs_inc_tax[$index] : 0;
        $addOrEditOpeningStock->subtotal = isset($request->subtotals[$index]) ? $request->subtotals[$index] : 0;
        $addOrEditOpeningStock->date = $date;
        $addOrEditOpeningStock->date_ts = date('Y-m-d H:i:s', strtotime($date.' 01:00:00'));
        $addOrEditOpeningStock->save();

        return $addOrEditOpeningStock;
    }
}
