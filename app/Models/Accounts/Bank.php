<?php

namespace App\Models\Accounts;
use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

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
