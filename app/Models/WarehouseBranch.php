<?php

namespace App\Models;

class WarehouseBranch extends BaseModel
{
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->select('id', 'name', 'branch_code');
    }
}
