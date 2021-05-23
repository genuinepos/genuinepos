<?php

namespace App\Models;

use App\Models\ExpanseCategory;
use Illuminate\Database\Eloquent\Model;

class ExpenseDescription extends Model
{
    public function category()
    {
        return $this->belongsTo(ExpanseCategory::class, 'expense_category_id');
    }
}
