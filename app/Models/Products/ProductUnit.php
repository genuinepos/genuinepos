<?php

namespace App\Models\Products;

use App\Models\Products\Unit;
use App\Models\Products\Product;
use App\Models\Products\ProductVariant;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    public function assignedUnit()
    {
        return $this->belongsTo(Unit::class, 'assigned_unit_id');
    }
}
