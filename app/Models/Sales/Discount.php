<?php

namespace App\Models\Sales;

use App\Models\BaseModel;
use App\Models\Sales\DiscountProduct;

class Discount extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function discountProducts()
    {
        return $this->hasMany(DiscountProduct::class);
    }
}
