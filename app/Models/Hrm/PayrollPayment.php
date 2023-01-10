<?php

namespace App\Models\Hrm;

use App\Models\Account;
use App\Models\PaymentMethod;
use App\Models\BaseModel;

class PayrollPayment extends BaseModel
{
    protected $table = 'hrm_payroll_payments';
    protected $guarded = [];
    protected $hidden = ['updated_at'];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
