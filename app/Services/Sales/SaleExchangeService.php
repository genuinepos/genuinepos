<?php

namespace App\Services\Sales;

use App\Enums\BooleanType;
use App\Enums\DiscountType;

class SaleExchangeService
{
    public function updateExchangeableSale(object $request, object $sale): object
    {
        $orderDiscount = $request->order_discount ? $request->order_discount : 0;
        $orderDiscountAmount = $request->order_discount_amount ? $request->order_discount_amount : 0;
        $orderTaxAmount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $change = $request->change_amount > 0 ? $request->change_amount : 0;
        $sale->net_total_amount = $sale->net_total_amount + $request->net_total_amount;
        $sale->total_invoice_amount = $sale->total_invoice_amount + $request->total_invoice_amount;
        $sale->order_discount_type = DiscountType::Fixed->value;
        $sale->order_discount = $sale->order_discount_amount + $orderDiscountAmount;
        $sale->order_discount_amount = $sale->order_discount_amount + $orderDiscountAmount;
        $sale->order_tax_amount = $sale->order_tax_amount + $orderTaxAmount;
        $sale->change_amount = $sale->change_amount + $change;
        $sale->exchange_status = BooleanType::True->value;
        $sale->save();

        return $sale;
    }

    public function prepareExchange(object $request, ?object $sale): object
    {
        $hasExchangeProduct = BooleanType::False->value;
        foreach ($sale?->saleProducts as $index => $saleProduct) {

            $__exQty = $request->ex_quantities[$index] ? $request->ex_quantities[$index] : 0;
            $variantId = $request->variant_ids[$index] == 'noid' ? null : $request->variant_ids[$index];

            if (
                ($__exQty != 0 && $__exQty != '') &&
                $saleProduct->product_id == $request->product_ids[$index] &&
                $saleProduct->variant_id == $variantId
            ) {

                $hasExchangeProduct = BooleanType::True->value;

                $saleProduct->ex_quantity = $__exQty;
                $saleProduct->ex_status = BooleanType::True->value;
                $saleProduct->unit_discount_type = $request->unit_discount_types[$index];
                $saleProduct->unit_discount = $request->unit_discounts[$index];
                $saleProduct->unit_discount_amounts = $request->unit_discount_amounts[$index];
                $saleProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
                $saleProduct->subtotal = $request->subtotals[$index];
            }
        }

        if ($hasExchangeProduct == BooleanType::False->value) {

            return ['pass' => false, 'msg' => __('Exchange can not go to the next step. All Product quantity is 0.')];
        }

        $sale->net_total_amount = $request->net_total_amount;

        return $sale;
    }
}
