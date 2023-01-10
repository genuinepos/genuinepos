<?php

namespace App\Models;
use App\Models\Branch;
use App\Models\BaseModel;

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
}
