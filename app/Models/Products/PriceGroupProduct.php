<?php

namespace App\Models\Products;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceGroupProduct extends BaseModel
{
    use HasFactory;

    public function priceGroupUnits()
    {
        return $this->hasMany(PriceGroupUnit::class, 'price_group_product_id');
    }
}
