<?php

namespace App\Models;
use App\Models\BaseModel;

class PriceGroup extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
}
