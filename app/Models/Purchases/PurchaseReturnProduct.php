<?php

namespace App\Models\Purchases;

use App\Models\BaseModel;
use App\Models\Products\Unit;
use App\Models\Products\Product;
use App\Models\Products\ProductVariant;
use App\Models\Purchases\PurchaseReturn;
use App\Models\Purchases\PurchaseProduct;

class PurchaseReturnProduct extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    public function purchaseProduct()
    {
        return $this->belongsTo(PurchaseProduct::class, 'purchase_product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
