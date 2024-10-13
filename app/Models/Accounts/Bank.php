<?php

namespace App\Models\Accounts;

use App\Models\BaseModel;
use App\Models\Accounts\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bank extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
