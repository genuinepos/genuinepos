<?php

namespace App\Models\Setups;

use Illuminate\Database\Eloquent\Model;

class BranchSetting extends Model
{
    public $timestamps = false;

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
