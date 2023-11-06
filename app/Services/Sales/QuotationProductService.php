<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Sales\SaleProduct;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class QuotationProductService
{
    public function updateQuotationProducts(object $request, object $quotation): void
    {
        $index = 0;
        foreach ($request->product_ids as $productId) {

            $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

            $quotationProduct = $this->singleQuotationProduct(id: $request->sale_product_ids[$index]);
            $addOrUpdateQuotationProduct = '';
            if ($quotationProduct) {

                $addOrUpdateQuotationProduct = $quotationProduct;
            } else {

                $addOrUpdateQuotationProduct = new SaleProduct();
            }

            $addOrUpdateQuotationProduct->sale_id = $quotation->id;
            $addOrUpdateQuotationProduct->product_id = $request->product_ids[$index];
            $addOrUpdateQuotationProduct->variant_id = $variantId;
            $addOrUpdateQuotationProduct->quantity = $request->quantities[$index];
            $addOrUpdateQuotationProduct->ordered_quantity = $quotation->order_status == 1 ? $request->quantities[$index] : 0;
            $addOrUpdateQuotationProduct->unit_discount_type = $request->unit_discount_types[$index];
            $addOrUpdateQuotationProduct->unit_discount = $request->unit_discounts[$index];
            $addOrUpdateQuotationProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
            $addOrUpdateQuotationProduct->tax_ac_id = $request->tax_ac_ids[$index];
            $addOrUpdateQuotationProduct->tax_type = $request->tax_types[$index];
            $addOrUpdateQuotationProduct->unit_tax_percent = $request->unit_tax_percents[$index];
            $addOrUpdateQuotationProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
            $addOrUpdateQuotationProduct->unit_id = $request->unit_ids[$index];
            $addOrUpdateQuotationProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
            $addOrUpdateQuotationProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
            $addOrUpdateQuotationProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
            $addOrUpdateQuotationProduct->subtotal = $request->subtotals[$index];
            $addOrUpdateQuotationProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
            $addOrUpdateQuotationProduct->is_delete_in_update = 0;
            $addOrUpdateQuotationProduct->save();

            $index++;
        }
    }

    function salesQuotationProducts(?array $with = null): ?object
    {
        $query = SaleProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    function singleQuotationProduct(?int $id, array $with = null): ?object
    {
        $query = SaleProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
