<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Unit extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
}
