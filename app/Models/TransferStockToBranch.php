<?php

namespace App\Models;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\BaseModel;
use App\Models\TransferStockToBranchProduct;

class TransferStockToBranch extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function Transfer_products()
    {
        return $this->hasMany(TransferStockToBranchProduct::class, 'transfer_stock_id');
    }
}
