<?php

namespace App\Models\Manufacturing;

use App\Models\BaseModel;
use App\Models\Products\Unit;
use App\Models\Products\Product;
use App\Models\Manufacturing\Process;
use App\Models\Products\ProductVariant;

class ProcessIngredient extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
