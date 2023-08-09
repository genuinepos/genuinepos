<?php

namespace App\Models;

class CashRegisterTransaction extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
