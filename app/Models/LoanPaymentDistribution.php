<?php

namespace App\Models;

class LoanPaymentDistribution extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
