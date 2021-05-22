<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id')->select(['id', 'name', 'branch_name']);
    }

    public function account_type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id')->select(['id', 'name']);
    }

    public function admin()
    {
        return $this->belongsTo(AdminAndUser::class, 'admin_id');
    }
}
