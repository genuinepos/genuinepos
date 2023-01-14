<?php

namespace App\Models;

use App\Models\ExpanseCategory;
use App\Models\BaseModel;

class ExpenseDescription extends BaseModel
{
    public function category()
    {
        return $this->belongsTo(ExpanseCategory::class, 'expense_category_id');
    }
}
