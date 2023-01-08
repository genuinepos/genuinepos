<?php

namespace App\Models;
use App\Models\ExpenseDescription;
use Illuminate\Database\Eloquent\Model;

class ExpanseCategory extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function expenseDescriptions()
    {
        return $this->hasMany(ExpenseDescription::class, 'expense_category_id');
    }
}
