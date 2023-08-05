<?php

namespace App\Models;

class Purchase extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $table = 'purchases';

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id')->select(['id', 'warehouse_name', 'warehouse_code', 'phone', 'address']);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->select(['id', 'name', 'branch_code', 'phone', 'city', 'state', 'zip_code', 'country', 'logo']);
    }

    public function purchase_products()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id');
    }

    public function purchase_order_products()
    {
        return $this->hasMany(PurchaseOrderProduct::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->select(['id', 'name', 'business_name', 'phone', 'email', 'address', 'prefix']);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function purchase_return()
    {
        return $this->hasOne(PurchaseReturn::class, 'purchase_id');
    }

    public function purchase_payments()
    {
        return $this->hasMany(PurchasePayment::class);
    }

    public function ledger()
    {
        return $this->hasOne(SupplierLedger::class);
    }

    public function transfer_to_warehouse()
    {
        return $this->hasMany(TransferStockToWarehouse::class);
    }

    public function transfer_to_branch()
    {
        return $this->hasMany(TransferStockToBranch::class);
    }
}
