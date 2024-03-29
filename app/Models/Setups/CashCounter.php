<?php

namespace App\Models\Setups;

use App\Models\BaseModel;

class CashCounter extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];
}
