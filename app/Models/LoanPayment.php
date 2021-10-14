<?php

namespace App\Models;

use App\Models\LoanPaymentDistribution;
use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    public function loan_payment_distributions()
    {
        return $this->hasMany(LoanPaymentDistribution::class);
    }
}
