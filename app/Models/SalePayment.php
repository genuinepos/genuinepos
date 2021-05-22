<?php

namespace App\Models;


use App\Models\Sale;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id')->select(['id', 'name', 'phone', 'business_name', 'total_sale_due']);
    }

    public function cashFlow()
    {
        return $this->hasOne(CashFlow::class, 'sale_payment_id');
    }
}
