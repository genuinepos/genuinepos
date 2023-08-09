<?php

namespace App\Models;

class LoanPayment extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function company()
    {
        return $this->belongsTo(LoanCompany::class, 'company_id');
    }

    public function PaymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function loan_payment_distributions()
    {
        return $this->hasMany(LoanPaymentDistribution::class);
    }
}
