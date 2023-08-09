<?php

namespace App\Models;

class PurchaseProduct extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function purchaseSaleChains()
    {
        return $this->hasMany(PurchaseSaleProductChain::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
