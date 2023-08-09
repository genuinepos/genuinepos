<?php

namespace App\Models;

class SalePayment extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function sale_return()
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id')->select(['id', 'name', 'phone', 'business_name', 'total_sale_due']);
    }

    public function cashFlow()
    {
        return $this->hasOne(CashFlow::class, 'sale_payment_id');
    }

    public function ledger()
    {
        return $this->hasOne(CustomerLedger::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
}
