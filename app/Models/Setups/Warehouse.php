<?php

namespace App\Models\Setups;

use App\Models\BaseModel;
use App\Models\Products\ProductOpeningStock;

class Warehouse extends BaseModel
{
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->select('id', 'name', 'branch_code');
    }

    public function warehouseBranches()
    {
        return $this->hasMany(WarehouseBranch::class, 'warehouse_id')->where('is_global', 0);
    }

    public function purchase()
    {
        return $this->hasMany(Purchase::class);
    }

    public function saleProduct()
    {
        return $this->hasMany(SaleProduct::class, 'stock_warehouse_id');
    }

    public function openingStockProduct()
    {
        return $this->hasOne(ProductOpeningStock::class, 'warehouse_id');
    }
}
