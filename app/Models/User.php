<?php

namespace App\Models;

use App\Models\Hrm\AllowanceEmployee;
use App\Models\Hrm\Attendance;
use App\Models\Hrm\Department;
use App\Models\Hrm\Designation;
use App\Models\Hrm\Shift;
use App\Models\Setups\Branch;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Billable, HasFactory, HasRoles, Notifiable;

    protected $guarded = [];

    protected $appends = ['role'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRoleAttribute()
    {
        return $this->roles?->first()?->name;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function employee_allowances()
    {
        return $this->belongsTo(AllowanceEmployee::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function isVerified()
    {
        return isset($this->email_verified_at);
    }
}
