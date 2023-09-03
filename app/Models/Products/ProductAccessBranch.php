<?php

namespace App\Models\Products;

use App\Models\Setups\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAccessBranch extends Model
{
    use HasFactory;

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
