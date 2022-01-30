<?php

namespace App\Utils;

use App\Models\Supplier;
use App\Models\SupplierLedger;
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
            ->join('purchases', 'purchase_returns.purchase_id', 'purchases.id')
            ->where('purchases.supplier_id', $supplierId)
            ->select(DB::raw('sum(total_return_amount) as total_inv_return_amt'))
            ->groupBy('purchases.supplier_id')->get();

        $totalSupplierReturn = DB::table('purchase_returns')
            ->where('purchase_returns.purchase_id', NULL)
            ->where('purchase_returns.supplier_id', $supplierId)
            ->select(
                DB::raw('sum(total_return_amount) as total_sup_return_amt')
            )->groupBy('purchase_returns.supplier_id')->get();

        $totalInvoiceReturnPayment = DB::table('purchase_payments') // Paid on purchase return invoice due.
            ->join('purchases', 'purchase_payments.purchase_id', 'purchases.id')
            ->where('purchase_payments.supplier_payment_id', NULL)
            ->where('purchase_payments.payment_type', 2)
            ->where('purchases.supplier_id', $supplierId)
            ->select(DB::raw('sum(paid_amount) as total_inv_return_paid'))
            ->groupBy('purchases.supplier_id')->get();

        $totalSupplierReturnPayment = DB::table('supplier_payments') // Paid on Total supplier return due.
            ->where('supplier_id', $supplierId)
            ->where('type', 2)
            ->select(DB::raw('sum(paid_amount) as sr_paid'))
            ->groupBy('supplier_id')->get();

        $__totalInvoiceReturnPayment = DB::table('purchase_payments') // Paid on supplier return invoice due.
            ->where('purchase_payments.purchase_id', NULL)
            ->where('purchase_payments.supplier_payment_id', NULL)
            ->where('purchase_payments.payment_type', 2)
            ->where('purchase_payments.supplier_id', $supplierId)
            ->select(DB::raw('sum(paid_amount) as total_inv_return_paid'))
            ->groupBy('purchase_payments.supplier_id')->get();

        $totalPurchase = $totalSupplierPurchase->sum('total_purchase');
        $totalPaid = $totalSupplierPayment->sum('s_paid') + $totalPurchasePayment->sum('p_paid');
        $totalReturn = $totalInvoiceReturn->sum('total_inv_return_amt')
            + $totalSupplierReturn->sum('total_sup_return_amt');

        $totalReturnPaid = $totalInvoiceReturnPayment->sum('total_inv_return_paid')
            + $totalSupplierReturnPayment->sum('sr_paid') 
            + $__totalInvoiceReturnPayment->sum('total_inv_return_paid');

        $totalDue = ($totalPurchase + $supplier->opening_balance + $totalReturnPaid) - $totalPaid - $totalReturn;
        $returnDue = $totalReturn - ($totalPurchase + $supplier->opening_balance - $totalPaid) - $totalReturnPaid;

        $supplier->total_purchase = $totalPurchase;
        $supplier->total_paid = $totalPaid;
        $supplier->total_purchase_due = $totalDue;
        $supplier->total_return = $totalReturn;
        $supplier->total_purchase_return_due = $returnDue > 0 ? $returnDue : 0;
        $supplier->save();
        return $totalDue;
    }

    public function voucherTypes()
    {
        return [
            1 => 'Purchases',
            2 => 'Purchase Return',
            3 => 'Payment',
            4 => 'Return Payment',
            5 => 'Paid To Supplier',
            6 => 'Received From Supplier',
        ];
    }

    public function voucherType($voucher_type_id)
    {
        $data = [
            0 => ['name' => 'Opening Balance', 'id' => 'purchase_id', 'voucher_no' => NULL, 'amt'=> 'credit'],
            1 => ['name' => 'Purchase', 'id' => 'purchase_id', 'voucher_no' => 'purchase_inv_id', 'amt'=> 'credit'],
            2 => ['name' => 'Purchase Return', 'id' => 'purchase_return_id', 'voucher_no' => 'return_inv_id', 'amt' => 'debit'],
            3 => ['name' => 'Purchase Payment', 'id' => 'purchase_payment_id', 'voucher_no' => 'payment_voucher_no', 'amt' => 'debit'],
            4 => ['name' => 'Received Return Amt.', 'id' => 'purchase_payment_id', 'voucher_no' => 'return_pay_voucher_no', 'amt' => 'credit'],
            5 => ['name' => 'Paid To Supplier', 'id' => 'supplier_payment_id', 'voucher_no' => 'supplier_payment_voucher', 'amt' => 'debit'],
            6 => ['name' => 'Return Amt. Received', 'id' => 'supplier_payment_id', 'voucher_no' => 'supplier_return_payment_voucher', 'amt' => 'credit'],
        ];

        return $data[$voucher_type_id];
    }

    public function addSupplierLedger($voucher_type_id, $supplier_id, $date, $trans_id, $amount)
    {
        $voucher_type = $this->voucherType($voucher_type_id);
        $addSupplierLedger = new SupplierLedger();
        $addSupplierLedger->supplier_id = $supplier_id;
        $addSupplierLedger->report_date = date('Y-m-d', strtotime($date.date(' H:i:s')));
        $addSupplierLedger->{$voucher_type['id']} = $trans_id;
        $addSupplierLedger->{$voucher_type['amt']} = $amount;
        $addSupplierLedger->amount = $amount;
        $addSupplierLedger->amount_type = $voucher_type['amt'];
        $addSupplierLedger->voucher_type = $voucher_type_id;
        $addSupplierLedger->running_balance = $this->adjustSupplierForSalePaymentDue($supplier_id);
        $addSupplierLedger->save();
    }

    public function updateSupplierLedger($voucher_type_id, $supplier_id, $date, $trans_id, $amount)
    {
        $voucher_type = $this->voucherType($voucher_type_id);
        $updateSupplierLedger = SupplierLedger::where($voucher_type['id'], $trans_id)->first();
        //$updateSupplierLedger->supplier_id = $supplier_id;
        $updateSupplierLedger->report_date = date('Y-m-d', strtotime($date.date(' H:i:s')));
        $updateSupplierLedger->{$voucher_type['amt']} = $amount;
        $updateSupplierLedger->amount = $amount;
        $updateSupplierLedger->running_balance = $this->adjustSupplierForSalePaymentDue($supplier_id);
        $updateSupplierLedger->save();
    }
}
