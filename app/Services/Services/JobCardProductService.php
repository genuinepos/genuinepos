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

    public function updateJobCardProducts(object $request, int $jobCardId): void
    {
        foreach ($request->product_ids as $index => $productId) {

            $addOrUpdateJobCardProduct = null;
            $jobCardProduct = $this->singleJobCardProduct(id: $request->job_card_product_ids[$index]);

            if (isset($jobCardProduct)) {

                $addOrUpdateJobCardProduct = $jobCardProduct;
            }else {

                $addOrUpdateJobCardProduct = new JobCardProduct();
            }

            $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $addOrUpdateJobCardProduct->job_card_id = $jobCardId;
            $addOrUpdateJobCardProduct->product_id = $productId;
            $addOrUpdateJobCardProduct->variant_id = $variantId;
            $addOrUpdateJobCardProduct->quantity = $request->quantities[$index];
            $addOrUpdateJobCardProduct->unit_discount_type = $request->unit_discount_types[$index];
            $addOrUpdateJobCardProduct->unit_discount = $request->unit_discounts[$index];
            $addOrUpdateJobCardProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
            $addOrUpdateJobCardProduct->tax_ac_id = $request->tax_ac_ids[$index];
            $addOrUpdateJobCardProduct->tax_type = $request->tax_types[$index];
            $addOrUpdateJobCardProduct->unit_tax_percent = $request->unit_tax_percents[$index];
            $addOrUpdateJobCardProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
            $addOrUpdateJobCardProduct->unit_id = $request->unit_ids[$index];
            $addOrUpdateJobCardProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
            $addOrUpdateJobCardProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
            $addOrUpdateJobCardProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
            $addOrUpdateJobCardProduct->subtotal = $request->subtotals[$index];
            $addOrUpdateJobCardProduct->save();
        }
    }

    public function singleJobCardProduct(?int $id, array $with = null): ?object
    {
        $query = JobCardProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
