<?php

namespace App\Models;

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

    public function sale_product()
    {
        return $this->hasMany(SaleProduct::class, 'stock_warehouse_id');
    }

    public function transfer_to_branch()
    {
        return $this->hasMany(TransferStockToWarehouse::class);
    }

    public function transfer_stock_branch()
    {
        return $this->hasMany(TransferStockToBranch::class);
    }

    public function transfer_stock_branch_to_branch()
    {
        return $this->hasMany(TransferStockBranchToBranch::class, 'sender_warehouse_id');
    }
}
