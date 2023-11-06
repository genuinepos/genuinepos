<?php

namespace App\Models\Setups;

use App\Models\BaseModel;

class PaymentMethod extends BaseModel
{
    protected $hidden = ['created_at', 'updated_at'];

    protected $guarded = [];

    public function branchDefaultAccount()
    {
        return $this->hasOne(PaymentMethodSetting::class, 'payment_method_id')
            ->where('branch_id', auth()->user()->branch_id);
    }

    public function paymentMethodSetting()
    {
        return $this->hasOne(PaymentMethodSetting::class, 'payment_method_id')->where('branch_id', auth()->user()->branch_id);
    }
}
