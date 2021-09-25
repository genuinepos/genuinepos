<?php

namespace App\Utils;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerUtil
{
    public function adjustCustomerAmountForSalePaymentDue($customerId)
    {
        $customer = Customer::where('id', $customerId)->first();
        $totalCustomerSale = DB::table('sales')->where('customer_id', $customerId)
            ->select(DB::raw('sum(total_payable_amount) as total_sale'))->groupBy('customer_id')->get();

        $totalCustomerPayment = DB::table('customer_payments')
            ->select(DB::raw('sum(paid_amount) as c_paid'))
            ->where('customer_id', $customerId)
            ->where('type', 1)
            ->groupBy('customer_id')->get();

        $totalSalePayment = DB::table('sale_payments')
            ->leftJoin('sales', 'sale_payments.sale_id', 'sales.id')
            ->where('sale_payments.customer_payment_id', NULL)
            ->where('sale_payments.payment_type', 1)
            ->where('sales.customer_id', $customerId)->select(DB::raw('sum(paid_amount) as s_paid'))
            ->groupBy('sales.customer_id')->get();

        $totalSaleReturn = DB::table('sale_returns')
            ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
            ->where('sales.customer_id', $customerId)
            ->select(DB::raw('sum(total_return_amount) as total_return_amt'))
            ->groupBy('sales.customer_id')->get();

        $totalInvoiceReturnPayment = DB::table('sale_payments') // Paid on invoice return due.
            ->join('sales', 'sale_payments.sale_id', 'sales.id')
            ->where('sale_payments.customer_payment_id', NULL)
            ->where('sale_payments.payment_type', 2)
            ->where('sales.customer_id', $customerId)
            ->select(DB::raw('sum(paid_amount) as total_inv_return_paid'))
            ->groupBy('sales.customer_id')->get();

        $totalCustomerReturnPayment = DB::table('customer_payments') // Paid on Total customer return due.
            ->where('customer_id', $customerId)
            ->where('type', 2)
            ->select(DB::raw('sum(paid_amount) as cr_paid'))
            ->groupBy('customer_id')->get();


        $totalSale = $totalCustomerSale->sum('total_sale');
        $totalPaid = $totalCustomerPayment->sum('c_paid') + $totalSalePayment->sum('s_paid');
        $totalReturn = $totalSaleReturn->sum('total_return_amt');
        $totalReturnPaid = $totalInvoiceReturnPayment->sum('total_inv_return_paid') + $totalCustomerReturnPayment->sum('cr_paid');
        $totalDue = ($totalSale + $customer->opening_balance + $totalReturnPaid) - $totalPaid - $totalReturn;
        $totalReturnDue = $totalReturn - ($totalSale - $totalPaid) - $totalReturnPaid;

        $customer->total_sale = $totalSale;
        $customer->total_paid = $totalPaid;
        $customer->total_sale_due = $totalDue;
        $customer->total_return = $totalReturn;
        $customer->total_sale_return_due = $totalReturnDue > 0 ? $totalReturnDue : 0;;
        $customer->save();
    }
}