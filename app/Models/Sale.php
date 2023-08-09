<?php

namespace App\Models;

class Sale extends BaseModel
{
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function sale_products()
    {
        return $this->hasMany(SaleProduct::class, 'sale_id');
    }

    public function sale_payments()
    {
        return $this->hasMany(SalePayment::class, 'sale_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function sale_return()
    {
        return $this->hasOne(SaleReturn::class, 'sale_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function ledger()
    {
        return $this->hasOne(CustomerLedger::class);
    }
}
