<?php

namespace App\Models;

use App\Models\Account;
use App\Models\CashFlow;
use App\Models\LoanCompany;
use App\Models\LoanPayment;
use App\Models\BaseModel;

class Loan extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function company()
    {
        return $this->belongsTo(LoanCompany::class, 'loan_company_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function cashFlow()
    {
        return $this->hasOne(CashFlow::class);
    }
}
