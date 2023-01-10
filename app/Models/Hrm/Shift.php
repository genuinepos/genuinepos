<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Shift extends BaseModel
{
    use HasFactory;
    protected $table = 'hrm_shifts';
    protected $fillable = ['shift_name','shift_type','start_time','endtime','holiday'];
}
