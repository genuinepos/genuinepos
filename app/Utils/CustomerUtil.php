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

        $totalSale = $totalCustomerSale->sum('total_sale');
        $totalPaid = $totalCustomerPayment->sum('c_paid') + $totalSalePayment->sum('s_paid');
        $totalDue = ($totalSale + $customer->opening_balance) - $totalPaid;

        $customer->total_sale = $totalSale;
        $customer->total_paid = $totalPaid;
        $customer->total_sale_due = $totalDue;
        $customer->save();
    }
}