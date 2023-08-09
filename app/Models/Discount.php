<?php

namespace App\Models;

class Discount extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function discountProducts()
    {
        return $this->hasMany(DiscountProduct::class);
    }
}
