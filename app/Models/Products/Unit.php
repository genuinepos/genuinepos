<?php

namespace App\Models\Products;

use App\Models\BaseModel;
use App\Models\Products\Product;

class Unit extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function childUnits()
    {
        return $this->hasMany(Unit::class, 'base_unit_id');
    }

    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'unit_id');
    }
}
