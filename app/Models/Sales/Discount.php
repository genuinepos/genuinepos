<?php

namespace App\Models\Sales;

use App\Models\BaseModel;
use App\Models\Setups\Branch;

class Discount extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function discountProducts()
    {
        return $this->hasMany(DiscountProduct::class);
    }
}
