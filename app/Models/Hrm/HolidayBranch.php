<?php

namespace App\Models\HRM;

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
}