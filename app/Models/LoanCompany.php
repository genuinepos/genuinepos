<?php

namespace App\Models;

class LoanCompany extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function loanPayments()
    {
        return $this->hasMany(LoanPayment::class, 'company_id');
    }
}
