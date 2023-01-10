<?php

namespace App\Models\Hrm;


use App\Models\BaseModel;

class PayrollAllowance extends BaseModel
{
    protected $table = 'hrm_payroll_allowances';
    protected $guarded = [];
    protected $hidden = ['updated_at'];
}
