<?php

namespace App\Models;

use App\Models\PaymentMethodSetting;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends BaseModel
{
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = [];

    public function methodAccount()
    {
        return $this->hasOne(PaymentMethodSetting::class, 'payment_method_id')
        ->where('branch_id', auth()->user()->branch_id)
        ->select('payment_method_id','account_id');
    }
}
