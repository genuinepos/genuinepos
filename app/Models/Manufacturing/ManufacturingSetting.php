<?php

namespace App\Models\Manufacturing;

use App\Models\Setups\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturingSetting extends Model
{
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
