<?php

namespace App\Models;

class CashRegister extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function cash_register_transactions()
    {
        return $this->hasMany(CashRegisterTransaction::class);
    }

    public function cash_counter()
    {
        return $this->belongsTo(CashCounter::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
