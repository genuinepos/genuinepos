<?php

namespace App\Services\Sales;

class SaleService
{
    public function adjustSaleInvoiceAmounts($sale)
    {
        $totalSaleReceived = DB::table('voucher_description_references')
            ->where('voucher_description_references.sale_id', $sale->id)
            ->select(DB::raw('sum(voucher_description_references.amount) as total_received'))
            ->groupBy('voucher_description_references.sale_id')
            ->get();

        $totalReturn = DB::table('sale_returns')
            ->where('sale_returns.sale_id', $sale->id)
            ->select(DB::raw('sum(total_return_amount) as total_returned_amount'))
            ->groupBy('sale_returns.sale_id')
            ->get();

        $due = $sale->total_payable_amount
            - $totalSaleReceived->sum('total_received')
            - $totalReturn->sum('total_returned_amount');

        $sale->paid = $totalPurchasePaid->sum('total_received');
        $sale->due = $due;
        $sale->sale_return_amount = $totalReturn->sum('total_returned_amount');
        $sale->save();

        return $sale;
    }
}
