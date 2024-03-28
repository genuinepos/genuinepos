<?php

namespace App\Models\Purchases;

use App\Models\BaseModel;
use App\Models\Products\Product;
use App\Models\Products\ProductVariant;
use App\Models\Products\StockChain;
use App\Models\Products\Unit;

class PurchaseProduct extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function stockChains()
    {
        return $this->hasMany(StockChain::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
