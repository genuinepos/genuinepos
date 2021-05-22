<?php

namespace App\Models;
use App\Models\Branch;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransferStockToWarehouseProduct;

class TransferStockToWarehouse extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];


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
