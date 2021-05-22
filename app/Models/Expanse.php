<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\AdminAndUser;
use App\Models\ExpanseCategory;
use Illuminate\Database\Eloquent\Model;

class Expanse extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function expanse_category()
    {
        return $this->belongsTo(ExpanseCategory::class, 'expanse_category_id');
    }

    public function expense_payments()
    {
        return $this->hasMany(ExpansePayment::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function admin()
    {
        return $this->belongsTo(AdminAndUser::class, 'admin_id');
    }
}
