<?php

namespace App\Models;
use App\Models\Bank;
use App\Models\CashFlow;
use App\Models\AdminAndUser;
use App\Models\AccountBranch;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
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

    public function cash_flows()
    {
        return $this->hasMany(CashFlow::class, 'account_id');
    }

    public function admin()
    {
        return $this->belongsTo(AdminAndUser::class, 'admin_id');
    }
}
