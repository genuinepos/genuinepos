<?php

namespace App\Models\Products;

use App\Models\BaseModel;

class PriceGroup extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];
}
