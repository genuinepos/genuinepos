<?php

namespace App\Models;

class Supplier extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function supplier_products()
    {
        return $this->hasMany(SupplierProduct::class)->where('label_qty', '>', 0);
    }

    public function purchase_returns()
    {
        return $this->hasMany(PurchaseReturn::class, 'supplier_id');
    }

    public function supplier_payments()
    {
        return $this->hasMany(SupplierPayment::class);
    }

    public function supplier_ledgers()
    {
        return $this->hasMany(SupplierLedger::class);
    }
}
