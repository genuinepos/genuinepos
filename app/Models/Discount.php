<?php

namespace App\Models;

use App\Models\DiscountProduct;
use App\Models\BaseModel;

class Discount extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function discountProducts()
    {
        return $this->hasMany(DiscountProduct::class);
    }
}
