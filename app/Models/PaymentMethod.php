<?php

namespace App\Models;

class PaymentMethod extends BaseModel
{
    protected $hidden = ['created_at', 'updated_at'];

    protected $guarded = [];

    public function methodAccount()
    {
        return $this->hasOne(PaymentMethodSetting::class, 'payment_method_id')
            ->where('branch_id', auth()->user()->branch_id)
            ->select('payment_method_id', 'account_id');
    }
}
