<?php

namespace App\Models;

use App\Models\SaleProduct;
use App\Models\PurchaseProduct;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
 
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function purchase_variants()
    {
        return $this->hasMany(PurchaseProduct::class, 'product_variant_id');
    }

    public function sale_variants()
    {
        return $this->hasMany(SaleProduct::class, 'product_variant_id');
    }
}
