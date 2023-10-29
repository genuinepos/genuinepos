<?php

namespace App\Models\Products;

use App\Models\BaseModel;
use App\Models\Setups\Branch;
use App\Models\Products\Product;
use App\Models\Products\ProductVariant;

class ProductOpeningStock extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
