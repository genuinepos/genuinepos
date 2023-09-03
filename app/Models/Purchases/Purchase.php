<?php

namespace App\Models\Purchases;

use App\Models\Branch;
use App\Models\BaseModel;
use App\Models\Setups\Warehouse;

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

    public function purchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id');
    }

    public function purchaseOrderProducts()
    {
        return $this->hasMany(PurchaseOrderProduct::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Account::class, 'supplier_account_id')->select(['id', 'name', 'business_name', 'phone', 'email', 'address', 'prefix']);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function purchaseReturn()
    {
        return $this->hasOne(PurchaseReturn::class, 'purchase_id');
    }

    // public function purchase_payments()
    // {
    //     return $this->hasMany(PurchasePayment::class);
    // }

    // public function ledger()
    // {
    //     return $this->hasOne(SupplierLedger::class);
    // }

    public function transferToWarehouse()
    {
        return $this->hasMany(TransferStockToWarehouse::class);
    }

    public function transferToBranch()
    {
        return $this->hasMany(TransferStockToBranch::class);
    }
}
