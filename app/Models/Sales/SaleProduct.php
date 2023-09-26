<?php

namespace App\Models\Sales;

use App\Models\BaseModel;
use App\Models\Sales\Sale;
use App\Models\Products\Unit;
use App\Models\Setups\Branch;
use App\Models\Products\Product;
use App\Models\Setups\Warehouse;
use App\Models\Products\ProductVariant;
use App\Models\Purchases\PurchaseSaleProductChain;

class SaleProduct extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function purchaseSaleProductChains()
    {
        return $this->hasMany(PurchaseSaleProductChain::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->select('id', 'name', 'branch_code');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id')->select('id', 'warehouse_name', 'warehouse_code');
    }
}
