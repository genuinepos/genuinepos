<?php

namespace App\Models;

class ExpenseDescription extends BaseModel
{
    public function category()
    {
        return $this->belongsTo(ExpanseCategory::class, 'expense_category_id');
    }
}
