<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Branch;
use App\Models\Hrm\AllowanceEmployee;
use App\Models\Hrm\Attendance;
use App\Models\Hrm\Department;
use App\Models\Hrm\Designation;
use App\Models\RolePermission;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

//use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    protected $guarded = [];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
}
