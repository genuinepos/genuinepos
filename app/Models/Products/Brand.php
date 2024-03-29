<?php

namespace App\Models\Products;

use App\Models\BaseModel;

class Brand extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id')->where('number_of_sale', '>', 0);
    }
}
