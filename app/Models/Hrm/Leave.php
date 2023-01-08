<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Hrm\LeaveType;
class Leave extends Model
{
    use HasFactory;
    protected $table = 'hrm_leaves';
    protected $fillable = ['reference_number','employee_id','leave_id','start_date','end_date','reason','status'];

     public function users()
    {
        return $this->belongsTo(User::class,'employee_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

}
