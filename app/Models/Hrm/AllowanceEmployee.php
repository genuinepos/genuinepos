<?php

namespace App\Models\Hrm;

use App\Models\BaseModel;
use App\Models\User;

class AllowanceEmployee extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function allowance()
    {
        return $this->belongsTo(Allowance::class, 'allowance_id');
    }
}
