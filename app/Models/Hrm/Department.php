<?php

namespace App\Models\Hrm;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends BaseModel
{
    use HasFactory;

    protected $table = 'hrm_department';

    protected $fillable = ['department_name', 'department_id', 'description'];
}
