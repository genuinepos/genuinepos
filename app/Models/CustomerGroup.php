<?php

namespace App\Models;

use App\Models\BaseModel;

class CustomerGroup extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
}
