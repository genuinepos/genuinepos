<?php

namespace App\Models\Products;

use App\Models\BaseModel;
use App\Models\Products\PriceGroupProduct;

class PriceGroup extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function priceGroupProducts()
    {
        return $this->hasMany(PriceGroupProduct::class, 'price_group_id');
    }
}
