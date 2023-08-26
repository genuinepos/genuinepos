<?php

namespace App\Models\Setups;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchSetting extends Model
{
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
