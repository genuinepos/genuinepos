<?php

namespace App\Models\Setups;

use App\Models\BaseModel;
use App\Models\Sales\CashRegister;

class CashCounter extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function cashRegisters()
    {
        return $this->hasMany(CashRegister::class, 'cash_counter_id');
    }
}
