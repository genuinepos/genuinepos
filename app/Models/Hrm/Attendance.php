<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Attendance extends BaseModel
{
    use HasFactory;
    protected $table = 'hrm_attendances';

    protected $fillable = ['at_date','user_id','clock_in','clock_out','work_duration','clock_in_note','clock_out_note','shift_id','month','year'];

}
