<?php

namespace App\Models\Products;

use App\Models\BaseModel;

class Unit extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function childUnits()
    {
        return $this->hasMany(Unit::class, 'base_unit_id');
    }
}
