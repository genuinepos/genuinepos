<?php

namespace App\Services\Sales;

use App\Enums\SaleStatus;
use App\Models\Sales\SaleProduct;
use Illuminate\Support\Facades\DB;

class SaleProductService
{
    public function addSaleProduct(object $request, object $sale, int $index): object
    {
        $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $warehouse_id = $request->warehouse_ids[$index] == 'NULL' ? null : $request->warehouse_ids[$index];
        $addSaleProduct = new SaleProduct();
        $addSaleProduct->sale_id = $sale->id;
        $addSaleProduct->warehouse_id = $warehouse_id;
        $addSaleProduct->branch_id = $sale->branch_id;
        $addSaleProduct->product_id = $product_id;
        $addSaleProduct->variant_id = $variant_id;
        $addSaleProduct->quantity = $request->quantities[$index];
        $addSaleProduct->ordered_quantity = $sale->status == SaleStatus::Order->value ? $request->quantities[$index] : 0;
        $addSaleProduct->left_quantity = $sale->status == SaleStatus::Order->value ? $request->quantities[$index] : 0;
        $addSaleProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addSaleProduct->unit_discount = $request->unit_discounts[$index];
        $addSaleProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addSaleProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addSaleProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addSaleProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addSaleProduct->unit_id = $request->unit_ids[$index];
        $addSaleProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addSaleProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addSaleProduct->unit_price_inc_tax = $request->unit_prices[$index];
        $addSaleProduct->subtotal = $request->subtotals[$index];
        $addSaleProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
        $addSaleProduct->save();

        return $addSaleProduct;
    }

    public function customerCopySaleProducts($saleId)
    {
        return DB::table('sale_products')
            ->where('sale_products.sale_id', $saleId)
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('warranties', 'products.warranty_id', 'warranties.id')
            ->leftJoin('product_variants', 'sale_products.variant_id', 'product_variants.id')
            ->leftJoin('units', 'sale_products.id_id', 'units.id')
            ->select(
                'sale_products.product_id',
                'sale_products.product_variant_id',
                'sale_products.description',
                'sale_products.unit_price_inc_tax',
                'sale_products.unit_discount_amount',
                'sale_products.unit_tax_percent',
                'sale_products.subtotal',
                'products.name as p_name',
                'products.product_code',
                'products.warranty_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'warranties.duration as w_duration',
                'warranties.duration_type as w_duration_type',
                'warranties.description as w_description',
                'warranties.type as w_type',
                'units.code_name as unit_name',
                DB::raw('SUM(sale_products.quantity) as quantity')
            )
            ->groupBy('sale_products.product_id')
            ->groupBy('sale_products.variant_id')
            ->groupBy('sale_products.description')
            ->groupBy('sale_products.unit_price_inc_tax')
            ->groupBy('sale_products.unit_discount_amount')
            ->groupBy('sale_products.unit_tax_percent')
            ->groupBy('sale_products.subtotal')
            ->groupBy('products.warranty_id')
            ->groupBy('products.name')
            ->groupBy('products.product_code')
            ->groupBy('warranties.duration')
            ->groupBy('warranties.duration_type')
            ->groupBy('warranties.type')
            ->groupBy('warranties.description')
            ->groupBy('product_variants.variant_name')
            ->groupBy('product_variants.variant_code')
            ->groupBy('units.code_name')
            ->get();
    }
}
