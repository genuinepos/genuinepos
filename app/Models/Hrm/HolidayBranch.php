<?php

namespace App\Models\Hrm;

use App\Models\Hrm\Holiday;
use App\Models\Setups\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HolidayBranch extends Model
{
    use HasFactory;

    protected $table = 'hrm_holiday_branches';

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function holiday()
    {
        return $this->belongsTo(Holiday::class, 'holiday_id');
    }
}
