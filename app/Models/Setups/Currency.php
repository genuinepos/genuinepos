<?php

namespace App\Models\Setups;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends BaseModel
{
    protected $table = 'currencies';
    use HasFactory;
}
