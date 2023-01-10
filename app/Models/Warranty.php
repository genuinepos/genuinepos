<?php

namespace App\Models;
use App\Models\BaseModel;

class Warranty extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
}
