<?php

namespace App\Models\Hrm;

use App\Models\User;
use App\Models\BaseModel;

class Payroll extends BaseModel
{
    protected $table = 'hrm_payrolls';
    protected $guarded = [];
    protected $hidden = ['updated_at'];

    public function payments()
    {
        return $this->hasMany(PayrollPayment::class, 'payroll_id');
    }

    public function allowances()
    {
        return $this->hasMany(PayrollAllowance::class, 'payroll_id');
    }

    public function deductions()
    {
        return $this->hasMany(PayrollDeduction::class, 'payroll_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }
}
