<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Expanse;
use App\Models\CashFlow;
use Illuminate\Database\Eloquent\Model;

class ExpansePayment extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function expense()
    {
        return $this->belongsTo(Expanse::class, 'expanse_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id')->select(['id', 'name', 'account_number', 'debit', 'credit', 'balance']);
    }

    public function cashFlow()
    {
        return $this->hasOne(CashFlow::class, 'expanse_payment_id');
    }

}
