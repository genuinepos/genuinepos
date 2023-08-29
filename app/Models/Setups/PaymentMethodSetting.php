<?php

namespace App\Models\Setups;

use App\Models\Branch;
use App\Models\Account;
use App\Models\BaseModel;
use App\Models\Setups\PaymentMethod;

class PaymentMethodSetting extends BaseModel
{
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
