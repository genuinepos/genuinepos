<?php

namespace App\Models\Manufacturing;

use App\Models\BaseModel;
use App\Models\Products\Unit;
use App\Models\Products\Product;
use App\Models\Products\ProductVariant;

class ProductionIngredient extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
