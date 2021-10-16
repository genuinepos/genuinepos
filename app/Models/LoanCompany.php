<?php

namespace App\Models;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Model;

class LoanCompany extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function loan()
    {
        return $this->hasMany(Loan::class);
    }
}
