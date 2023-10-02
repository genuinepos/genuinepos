<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Sales\SaleProduct;
use Illuminate\Support\Facades\DB;

class DraftProductService
{
    public function updateDraftProducts(object $request, object $draft): void
    {
        $index = 0;
        foreach ($request->product_ids as $productId) {

            $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

            $draftProduct = $this->singleDraftProduct(id: $request->sale_product_ids[$index]);
            $addOrUpdateDraftProduct = '';
            if ($draftProduct) {

                $addOrUpdateDraftProduct = $draftProduct;
            } else {

                $addOrUpdateDraftProduct = new SaleProduct();
            }

            $addOrUpdateDraftProduct->sale_id = $draft->id;
            $addOrUpdateDraftProduct->product_id = $request->product_ids[$index];
            $addOrUpdateDraftProduct->variant_id = $variantId;
            $addOrUpdateDraftProduct->quantity = $request->quantities[$index];
            $addOrUpdateDraftProduct->unit_discount_type = $request->unit_discount_types[$index];
            $addOrUpdateDraftProduct->unit_discount = $request->unit_discounts[$index];
            $addOrUpdateDraftProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
            $addOrUpdateDraftProduct->tax_ac_id = $request->tax_ac_ids[$index];
            $addOrUpdateDraftProduct->unit_tax_percent = $request->unit_tax_percents[$index];
            $addOrUpdateDraftProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
            $addOrUpdateDraftProduct->unit_id = $request->unit_ids[$index];
            $addOrUpdateDraftProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
            $addOrUpdateDraftProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
            $addOrUpdateDraftProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
            $addOrUpdateDraftProduct->subtotal = $request->subtotals[$index];
            $addOrUpdateDraftProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
            $addOrUpdateDraftProduct->is_delete_in_update = 0;
            $addOrUpdateDraftProduct->save();

            $index++;
        }
    }

    function draftProducts(?array $with = null): ?object
    {
        $query = SaleProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    function singleDraftProduct(?int $id, array $with = null): ?object
    {
        $query = SaleProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
