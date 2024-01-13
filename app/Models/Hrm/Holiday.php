<?php

namespace App\Models\Hrm;

use App\Models\BaseModel;
use App\Models\HRM\HolidayBranch;

class Holiday extends BaseModel
{
    protected $table = 'hrm_holidays';

    public function allowedBranches()
    {
        return $this->hasMany(HolidayBranch::class, 'holiday_id');
    }
}
