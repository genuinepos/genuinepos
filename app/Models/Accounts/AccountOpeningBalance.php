<?php

namespace App\Models\Accounts;

use App\Models\Branches\Branch;
use App\Models\Accounts\Account;
use Illuminate\Database\Eloquent\Model;

class AccountOpeningBalance extends Model
{
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
