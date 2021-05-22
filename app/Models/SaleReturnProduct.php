<?php

namespace App\Models;

use App\Models\SaleReturn;
use App\Models\SaleProduct;
use Illuminate\Database\Eloquent\Model;

class SaleReturnProduct extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function sale_return()
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id');
    }

    public function sale_product()
    {
        return $this->belongsTo(SaleProduct::class, 'sale_product_id');
    }
}
