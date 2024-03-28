<?php

namespace App\Models\Products;

use App\Models\User;
use App\Models\Setups\Branch;
use App\Models\Hrm\Department;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products\StockIssueProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockIssue extends Model
{
    use HasFactory;

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function stockIssuedProducts()
    {
        return $this->hasMany(StockIssueProduct::class, 'stock_issue_id');
    }
}
