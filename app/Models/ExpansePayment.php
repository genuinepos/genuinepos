<?php

namespace App\Models;

class ExpansePayment extends BaseModel
{
    //protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function expense()
    {
        return $this->belongsTo(Expanse::class, 'expanse_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function cashFlow()
    {
        return $this->hasOne(CashFlow::class, 'expanse_payment_id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
