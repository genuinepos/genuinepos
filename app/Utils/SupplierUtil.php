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

        $totalInvoiceReturn = DB::table('purchase_returns')
        ->leftJoin('purchases', 'purchase_returns.purchase_id', 'purchases.id')
        ->where('purchases.supplier_id', $supplierId)
        ->select(DB::raw('sum(total_return_amount) as total_inv_return_amt'))
        ->groupBy('purchases.supplier_id')->get();

        $totalSupplierReturn = DB::table('purchase_returns')
        ->where('purchase_returns.purchase_id', NULL)
        ->where('purchase_returns.supplier_id', $supplierId)
        ->select(
            DB::raw('sum(total_return_amount) as total_sup_return_amt')
        )->groupBy('purchase_returns.supplier_id')->get();

        $totalInvoiceReturnPayment = DB::table('purchase_payments')
            ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
            ->where('purchase_payments.supplier_payment_id', NULL)
            ->where('purchase_payments.payment_type', 2)
            ->where('purchases.supplier_id', $supplierId)
            ->select(DB::raw('sum(paid_amount) as total_inv_return_paid'))
            ->groupBy('purchases.supplier_id')->get();

        $totalSupplierReturnPayment = DB::table('purchase_payments')
            ->where('purchase_payments.supplier_id', $supplierId)
            ->where('purchase_payments.purchase_id', NULL)
            ->where('purchase_payments.payment_type', 2)
            ->select(DB::raw('sum(paid_amount) as total_sup_return_paid'))
            ->groupBy('supplier_id')->get();

        $totalPurchase = $totalSupplierPurchase->sum('total_purchase');
        $totalPaid = $totalSupplierPayment->sum('s_paid') + $totalPurchasePayment->sum('p_paid');
        $totalReturn = $totalInvoiceReturn->sum('total_inv_return_amt') + $totalSupplierReturn->sum('total_sup_return_amt');
        $totalReturnPaid = $totalInvoiceReturnPayment->sum('total_inv_return_paid') + $totalSupplierReturnPayment->sum('total_sup_return_paid');
        $totalDue = ($totalPurchase + $supplier->opening_balance + $totalReturnPaid) - $totalPaid - $totalReturn;
        $returnDue = $totalReturn - ($totalPurchase - $totalPaid);
        $supplier->total_purchase = $totalPurchase;
        $supplier->total_paid = $totalPaid;
        $supplier->total_purchase_due = $totalDue;
        $supplier->total_return = $totalReturn;
        $supplier->total_purchase_return_due = $returnDue > 0 ? $returnDue : 0;
        $supplier->save();
    }
}
