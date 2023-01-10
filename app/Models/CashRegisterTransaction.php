<?php

namespace App\Models;

use App\Models\Sale;
use App\Models\BaseModel;

class CashRegisterTransaction extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['updated_at'];
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
