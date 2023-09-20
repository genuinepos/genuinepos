<?php

namespace App\Models\Sales;

use App\Models\User;
use App\Models\BaseModel;
use App\Models\SaleReturn;
use App\Models\SaleProduct;
use App\Models\Setups\Branch;
use App\Models\Accounts\Account;

class Sale extends BaseModel
{
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class, 'sale_id');
    }

    public function customer()
    {
        return $this->belongsTo(Account::class, 'customer_account_id');
    }

    public function saleReturn()
    {
        return $this->hasOne(SaleReturn::class, 'sale_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
