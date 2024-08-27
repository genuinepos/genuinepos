<?php

namespace App\Models\Products;

use App\Models\Branches\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAccessBranch extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at', 'is_delete_in_update'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
