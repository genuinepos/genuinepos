<?php

namespace App\Models\Hrm;
use App\Models\BaseModel;

class PayrollDeduction extends BaseModel
{
    protected $table = 'hrm_payroll_deductions';
    protected $guarded = [];
    protected $hidden = ['updated_at'];
}
