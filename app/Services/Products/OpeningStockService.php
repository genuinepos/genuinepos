<?php

namespace App\Services\Products;

use App\Models\Products\ProductOpeningStock;

class OpeningStockService
{
    public function addOrEditProductOpeningStock(object $request, int $index): object
    {
        $generalSettings = config('generalSettings');
        $accountStartDate = $generalSettings['business__start_date'];
        $date = $accountStartDate;

        $addOrEditOpeningStock = '';

        $openingStock = ProductOpeningStock::where('branch_id', $request->branch_ids[$index])
            ->where('warehouse_id', $request->warehouse_ids[$index])
            ->where('product_id', $request->product_ids[$index])
            ->where('variant_id', $request->variant_ids[$index])->first();

        if ($openingStock) {

            $addOrEditOpeningStock = $openingStock;
            $date = $openingStock->date;
        } else {

            $addOrEditOpeningStock = new ProductOpeningStock();
        }

        $addOrEditOpeningStock->branch_id = $request->branch_ids[$index];
        $addOrEditOpeningStock->warehouse_id = $request->warehouse_ids[$index];
        $addOrEditOpeningStock->product_id = $request->product_ids[$index];
        $addOrEditOpeningStock->variant_id = $request->variant_ids[$index];
        $addOrEditOpeningStock->quantity = $request->quantities[$index];
        $addOrEditOpeningStock->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrEditOpeningStock->subtotal = $request->subtotals[$index];
        $addOrEditOpeningStock->subtotal = $request->subtotals[$index];
        $addOrEditOpeningStock->date = $date;
        $addOrEditOpeningStock->date_ts = date('Y-m-d H:i:s', strtotime($date.' 01:00:00'));
        $addOrEditOpeningStock->save();

        return $addOrEditOpeningStock;
    }
}
