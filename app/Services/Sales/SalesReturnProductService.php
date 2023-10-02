<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Sales\SaleReturnProduct;
use Yajra\DataTables\Facades\DataTables;

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
}
