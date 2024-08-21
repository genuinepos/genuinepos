<?php

namespace App\Models\Hrm;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Allowance extends BaseModel
{
    use HasFactory;

    protected $table = 'hrm_allowances';
}
