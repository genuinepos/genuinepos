<?php

namespace App\Models\Setups;

use App\Models\BaseModel;

class BarcodeSetting extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
}
