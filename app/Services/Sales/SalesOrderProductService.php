<?php

namespace App\Services\Sales;

use App\Models\Sales\SaleProduct;

class SalesOrderProductService
{
    public function updateSalesOrderProducts(object $request, object $salesOrder): void
    {
        $index = 0;
        foreach ($request->product_ids as $productId) {

            $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

            $salesOrderProduct = $this->singleSalesOrderProduct(id: $request->sale_product_ids[$index]);
            $addOrUpdateSalesOrderProduct = '';
            if ($salesOrderProduct) {

                $addOrUpdateSalesOrderProduct = $salesOrderProduct;
            } else {

                $addOrUpdateSalesOrderProduct = new SaleProduct();
            }

            $addOrUpdateSalesOrderProduct->sale_id = $salesOrder->id;
            $addOrUpdateSalesOrderProduct->product_id = $request->product_ids[$index];
            $addOrUpdateSalesOrderProduct->variant_id = $variantId;
            $addOrUpdateSalesOrderProduct->quantity = $request->quantities[$index];
            $addOrUpdateSalesOrderProduct->ordered_quantity = $request->quantities[$index];
            $addOrUpdateSalesOrderProduct->unit_discount_type = $request->unit_discount_types[$index];
            $addOrUpdateSalesOrderProduct->unit_discount = $request->unit_discounts[$index];
            $addOrUpdateSalesOrderProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
            $addOrUpdateSalesOrderProduct->tax_ac_id = $request->tax_ac_ids[$index];
            $addOrUpdateSalesOrderProduct->tax_type = $request->tax_types[$index];
            $addOrUpdateSalesOrderProduct->unit_tax_percent = $request->unit_tax_percents[$index];
            $addOrUpdateSalesOrderProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
            $addOrUpdateSalesOrderProduct->unit_id = $request->unit_ids[$index];
            $addOrUpdateSalesOrderProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
            $addOrUpdateSalesOrderProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
            $addOrUpdateSalesOrderProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
            $addOrUpdateSalesOrderProduct->subtotal = $request->subtotals[$index];
            $addOrUpdateSalesOrderProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
            $addOrUpdateSalesOrderProduct->is_delete_in_update = 0;
            $addOrUpdateSalesOrderProduct->save();

            $index++;
        }
    }

    public function salesOrderProducts(array $with = null): ?object
    {
        $query = SaleProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singleSalesOrderProduct(?int $id, array $with = null): ?object
    {
        $query = SaleProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
