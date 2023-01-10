<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Department extends BaseModel
{
    use HasFactory;
    protected $table = 'hrm_department';
    protected $fillable = ['department_name','department_id','description'];
}
