<?php

namespace App\Services\Sales;

use App\Models\Sales\SaleProduct;

class SaleExchangeProduct
{
    public function addSaleExchangeProduct(object $request, object $sale, int $index)
    {
        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $addSaleProduct = new SaleProduct();
        $addSaleProduct->sale_id = $sale->id;
        $addSaleProduct->branch_id = $sale->branch_id;
        $addSaleProduct->product_id = $request->product_ids[$index];
        $addSaleProduct->variant_id = $variantId;
        $addSaleProduct->quantity = $request->quantities[$index];
        $addSaleProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addSaleProduct->unit_discount = $request->unit_discounts[$index];
        $addSaleProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addSaleProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addSaleProduct->tax_type = $request->tax_types[$index];
        $addSaleProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addSaleProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addSaleProduct->unit_id = $request->unit_ids[$index];
        $addSaleProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addSaleProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addSaleProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addSaleProduct->subtotal = $request->subtotals[$index];
        $addSaleProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
        $addSaleProduct->ex_quantity = $request->quantities[$index];
        $addSaleProduct->ex_status = 1;
        $addSaleProduct->save();

        return $addSaleProduct;
    }
}
