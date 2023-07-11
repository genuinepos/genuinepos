<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id')->select(['id', 'name', 'branch_name']);
    }

    public function accountBranches()
    {
        return $this->hasMany(AccountBranch::class);
    }

    public function accountLedgers()
    {
        return $this->hasMany(AccountLedger::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
