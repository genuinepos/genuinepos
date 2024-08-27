<?php

namespace App\Models\Sales;

use App\Models\BaseModel;
use App\Models\Branches\Branch;
use App\Models\Setups\CashCounter;
use App\Models\User;

class CashRegister extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function transactions()
    {
        return $this->hasMany(CashRegisterTransaction::class);
    }

    public function cashCounter()
    {
        return $this->belongsTo(CashCounter::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
