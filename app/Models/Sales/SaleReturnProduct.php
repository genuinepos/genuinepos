<?php

namespace App\Models\Sales;

use App\Models\BaseModel;
use App\Models\Products\Product;
use App\Models\Products\ProductVariant;
use App\Models\Products\Unit;
use App\Models\Purchases\PurchaseProduct;

class SaleReturnProduct extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function salesReturn()
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id');
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

    public function saleProduct()
    {
        return $this->belongsTo(SaleProduct::class, 'sale_product_id');
    }

    public function purchaseProduct()
    {
        return $this->belongsTo(PurchaseProduct::class, 'sale_return_product_id', 'id');
    }
}
