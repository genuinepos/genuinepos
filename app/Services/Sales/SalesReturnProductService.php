<?php

namespace App\Services\Sales;

use App\Enums\BooleanType;
use App\Models\Sales\SaleReturnProduct;

class SalesReturnProductService
{
    public function addSalesReturnProduct(object $request, int $saleReturnId, int $index): object
    {
        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $addSaleReturnProduct = new SaleReturnProduct();
        $addSaleReturnProduct->sale_return_id = $saleReturnId;
        $addSaleReturnProduct->sale_product_id = $request->sale_product_ids[$index];
        $addSaleReturnProduct->product_id = $request->product_ids[$index];
        $addSaleReturnProduct->variant_id = $variantId;
        $addSaleReturnProduct->return_qty = $request->return_quantities[$index];
        $addSaleReturnProduct->sold_quantity = $request->sold_quantities[$index];
        $addSaleReturnProduct->unit_id = $request->unit_ids[$index];
        $addSaleReturnProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addSaleReturnProduct->unit_discount = $request->unit_discounts[$index];
        $addSaleReturnProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addSaleReturnProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addSaleReturnProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addSaleReturnProduct->tax_type = $request->tax_types[$index];
        $addSaleReturnProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addSaleReturnProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addSaleReturnProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addSaleReturnProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addSaleReturnProduct->return_subtotal = $request->subtotals[$index];
        $addSaleReturnProduct->save();

        return $addSaleReturnProduct;
    }

    public function updateSalesReturnProduct(object $request, int $saleReturnId, int $index): object
    {
        $returnProduct = $this->singleSalesReturnProduct(id: $request->sale_return_product_ids[$index]);
        $currentUnitTaxAcId = isset($returnProduct) ? $returnProduct->tax_ac_id : null;
        $addOrEditSaleReturnProduct = '';
        if (isset($returnProduct)) {

            $addOrEditSaleReturnProduct = $returnProduct;
        } else {

            $addOrEditSaleReturnProduct = new SaleReturnProduct();
        }

        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $addOrEditSaleReturnProduct->sale_return_id = $saleReturnId;
        $addOrEditSaleReturnProduct->sale_product_id = $request->sale_product_ids[$index];
        $addOrEditSaleReturnProduct->product_id = $request->product_ids[$index];
        $addOrEditSaleReturnProduct->variant_id = $variantId;
        $addOrEditSaleReturnProduct->return_qty = $request->return_quantities[$index];
        $addOrEditSaleReturnProduct->sold_quantity = $request->sold_quantities[$index];
        $addOrEditSaleReturnProduct->unit_id = $request->unit_ids[$index];
        $addOrEditSaleReturnProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addOrEditSaleReturnProduct->unit_discount = $request->unit_discounts[$index];
        $addOrEditSaleReturnProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addOrEditSaleReturnProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addOrEditSaleReturnProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addOrEditSaleReturnProduct->tax_type = $request->tax_types[$index];
        $addOrEditSaleReturnProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addOrEditSaleReturnProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addOrEditSaleReturnProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addOrEditSaleReturnProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrEditSaleReturnProduct->return_subtotal = $request->subtotals[$index];
        $addOrEditSaleReturnProduct->is_delete_in_update = BooleanType::False->value;
        $addOrEditSaleReturnProduct->save();

        $addOrEditSaleReturnProduct->current_tax_ac_id = $currentUnitTaxAcId;

        return $addOrEditSaleReturnProduct;
    }

    public function singleSalesReturnProduct(?int $id, array $with = null): ?object
    {
        $query = SaleReturnProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function salesReturnProducts(array $with = null): ?object
    {
        $query = SaleReturnProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
