<?php

namespace App\Models\Hrm;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends BaseModel
{
    use HasFactory;

    protected $table = 'hrm_departments';

    protected $fillable = ['name', 'department_id', 'description'];
}
