<?php

namespace App\Models\Setup;

use App\Models\Account;
use App\Models\BaseModel;

class PaymentMethodSetting extends BaseModel
{
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
