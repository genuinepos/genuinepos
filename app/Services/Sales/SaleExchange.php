<?php

namespace App\Services\Sales;

class SaleExchange
{
    public function updateExchangeableSale(object $request, object $sale): object
    {
        $change = $request->change_amount > 0 ? $request->change_amount : 0;
        $sale->net_total_amount = $sale->net_total_amount + $request->net_total_amount;
        $sale->total_invoice_amount = $sale->total_invoice_amount + $request->total_invoice_amount;
        $sale->order_discount_type = 1;
        $sale->order_discount = $request->order_discount_amount ? $request->order_discount_amount : 0.00;
        $sale->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0.00;
        $sale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
        $sale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $sale->change_amount = $sale->change_amount + $change;
        $sale->exchange_status = 1;
        $sale->save();
    }
}
