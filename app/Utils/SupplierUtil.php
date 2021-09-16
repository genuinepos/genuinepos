<?php

namespace App\Utils;

use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierUtil
{
    public function adjustSupplierForSalePaymentDue($supplierId)
    {
        $supplier = Supplier::where('id', $supplierId)->first();
        $totalSupplierPurchase = DB::table('purchases')
            ->where('supplier_id', $supplierId)
            ->select(DB::raw('sum(total_purchase_amount) as total_purchase'))
            ->groupBy('supplier_id')->get();

        $totalSupplierPayment = DB::table('supplier_payments')
            ->where('supplier_id', $supplierId)
            ->where('type', 1)
            ->select(DB::raw('sum(paid_amount) as s_paid'))
            ->groupBy('supplier_id')->get();

        $totalPurchasePayment = DB::table('purchase_payments')
            ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
            ->where('purchase_payments.supplier_payment_id', NULL)
            ->where('purchase_payments.payment_type', 1)
            ->where('purchases.supplier_id', $supplierId)
            ->select(DB::raw('sum(paid_amount) as p_paid'))
            ->groupBy('purchases.supplier_id')->get();

        $totalPurchase = $totalSupplierPurchase->sum('total_purchase');
        $totalPaid = $totalSupplierPayment->sum('s_paid') + $totalPurchasePayment->sum('p_paid');
        $totalDue = ($totalPurchase + $supplier->opening_balance) - $totalPaid;

        $supplier->total_purchase = $totalPurchase;
        $supplier->total_paid = $totalPaid;
        $supplier->total_purchase_due = $totalDue;
        $supplier->save();
    }
}
