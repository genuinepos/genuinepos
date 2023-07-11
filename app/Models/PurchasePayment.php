<?php

namespace App\Models;

class PurchasePayment extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function cashFlow()
    {
        return $this->hasOne(CashFlow::class, 'purchase_payment_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->select(['id', 'name', 'phone', 'business_name', 'total_purchase_due']);
    }

    public function ledger()
    {
        return $this->hasOne(SupplierLedger::class);
    }
}
