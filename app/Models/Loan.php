<?php

namespace App\Models;

use App\Models\LoanCompany;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function company()
    {
        return $this->belongsTo(LoanCompany::class, 'loan_company_id');
    }
}
