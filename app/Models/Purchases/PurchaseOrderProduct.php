<?php

namespace App\Models\Purchases;

use App\Models\BaseModel;
use App\Models\Products\Unit;
use App\Models\Products\Product;
use App\Models\Products\ProductVariant;
use App\Models\Purchases\PurchaseProduct;

class PurchaseOrderProduct extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function purchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_order_product_id');
    }
}
