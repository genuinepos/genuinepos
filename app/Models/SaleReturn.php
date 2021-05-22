<?php

namespace App\Models;

use App\Models\Sale;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\SaleReturnProduct;
use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function sale_return_products()
    {
        return $this->hasMany(SaleReturnProduct::class);
    }
}
