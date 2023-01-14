<?php

namespace App\Models;

use App\Models\Loan;
use App\Models\BaseModel;

class LoanPaymentDistribution extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
