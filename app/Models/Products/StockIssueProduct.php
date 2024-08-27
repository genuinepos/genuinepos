<?php

namespace App\Models\Products;

use App\Models\Products\Unit;
use App\Models\Branches\Branch;
use App\Models\Products\Product;
use App\Models\Setups\Warehouse;
use App\Models\Products\StockChain;
use App\Models\Products\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockIssueProduct extends Model
{
    use HasFactory;

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

    public function stockBranch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function stockWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function stockChains()
    {
        return $this->hasMany(StockChain::class, 'stock_issue_product_id');
    }
}
