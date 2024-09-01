<?php

namespace App\Models\Sales;

use App\Models\BaseModel;
use App\Models\Products\Product;
use App\Models\Products\ProductVariant;
use App\Models\Products\StockChain;
use App\Models\Products\Unit;
use App\Models\Branches\Branch;
use App\Models\Setups\Warehouse;

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

    public function stockChains()
    {
        return $this->hasMany(StockChain::class, 'sale_product_id');
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
