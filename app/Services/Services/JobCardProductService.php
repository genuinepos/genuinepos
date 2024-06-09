<?php

namespace App\Services\Services;


use App\Models\Services\JobCardProduct;
use Illuminate\Support\Facades\DB;

class JobCardProductService
{
    public function addJobCardProducts(object $request, int $jobCardId): void
    {
        foreach ($request->product_ids as $index => $productId) {

            $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $addJobCardProduct = new JobCardProduct();
            $addJobCardProduct->job_card_id = $jobCardId;
            $addJobCardProduct->product_id = $productId;
            $addJobCardProduct->variant_id = $variantId;
            $addJobCardProduct->quantity = $request->quantities[$index];
            $addJobCardProduct->unit_discount_type = $request->unit_discount_types[$index];
            $addJobCardProduct->unit_discount = $request->unit_discounts[$index];
            $addJobCardProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
            $addJobCardProduct->tax_ac_id = $request->tax_ac_ids[$index];
            $addJobCardProduct->tax_type = $request->tax_types[$index];
            $addJobCardProduct->unit_tax_percent = $request->unit_tax_percents[$index];
            $addJobCardProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
            $addJobCardProduct->unit_id = $request->unit_ids[$index];
            $addJobCardProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
            $addJobCardProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
            $addJobCardProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
            $addJobCardProduct->subtotal = $request->subtotals[$index];
            $addJobCardProduct->save();
        }
    }
}
