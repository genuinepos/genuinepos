<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\User;
use App\Models\ExpenseDescription;
use App\Models\BaseModel;

class Expanse extends BaseModel
{
    //protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function expense_descriptions()
    {
        return $this->hasMany(ExpenseDescription::class, 'expense_id');
    }

    public function expense_payments()
    {
        return $this->hasMany(ExpansePayment::class, 'expanse_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
