<?php

namespace App\Services\Sales;

class SaleExchange
{
    public function updateExchangeableSale(object $request, object $sale): object
    {

        $orderDiscount = $request->order_discount ? $request->order_discount : 0;
        $orderDiscountAmount = $request->order_discount_amount ? $request->order_discount_amount : 0;
        $orderTaxAmount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $change = $request->change_amount > 0 ? $request->change_amount : 0;
        $sale->net_total_amount = $sale->net_total_amount + $request->net_total_amount;
        $sale->total_invoice_amount = $sale->total_invoice_amount + $request->total_invoice_amount;
        $sale->order_discount = $sale->order_discount + $orderDiscount;
        $sale->order_discount_amount = $sale->order_discount_amount + $orderDiscountAmount;
        $sale->order_tax_amount = $sale->order_tax_amount + $orderTaxAmount;
        $sale->change_amount = $sale->change_amount + $change;
        $sale->exchange_status = 1;
        $sale->save();

        return $sale;
    }
}
