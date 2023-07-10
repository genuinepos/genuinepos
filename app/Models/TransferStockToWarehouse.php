<?php

namespace App\Models;

// use App\Models\User;

class TransferStockToWarehouse extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    // public function admin()
    // {
    //     return $this->belongsTo(User::class, 'admin_id');
    // }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function Transfer_products()
    {
        return $this->hasMany(TransferStockToWarehouseProduct::class, 'transfer_stock_id');
    }
}
