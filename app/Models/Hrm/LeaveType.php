<?php

namespace App\Models\Hrm;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveType extends BaseModel
{
    use HasFactory;

    protected $table = 'hrm_leavetypes';

    protected $fillable = ['leave_type', 'max_leave_count', 'leave_count_interval'];
}
