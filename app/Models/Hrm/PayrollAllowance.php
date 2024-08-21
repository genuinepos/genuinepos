<?php

namespace App\Models\Hrm;

use App\Models\BaseModel;
use App\Models\Hrm\Allowance;

class PayrollAllowance extends BaseModel
{
    protected $table = 'hrm_payroll_allowances';

    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function allowance()
    {
        return $this->belongsTo(Allowance::class, 'allowance_id');
    }
}
