<?php

namespace App\Models\Hrm;

use App\Models\BaseModel;

class Designation extends BaseModel
{
    protected $table = 'hrm_designations';

    protected $fillable = ['designation_name', 'description'];
}
