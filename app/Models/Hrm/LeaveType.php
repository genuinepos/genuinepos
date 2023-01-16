<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class LeaveType extends BaseModel
{
    use HasFactory;
     protected $table = 'hrm_leavetypes';

    protected $fillable = ['leave_type','max_leave_count','leave_count_interval'];
}
