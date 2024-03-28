<?php

namespace App\Models\StockAdjustments;

use App\Models\BaseModel;
use App\Models\Products\Product;
use App\Models\Products\ProductVariant;
use App\Models\Products\StockChain;
use App\Models\Products\Unit;
use App\Models\Setups\Branch;
use App\Models\Setups\Warehouse;

class StockAdjustmentProduct extends BaseModel
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

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class, 'stock_adjustment_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function stockChains()
    {
        return $this->belongsTo(StockChain::class, 'stock_adjustment_product_id');
    }
}
