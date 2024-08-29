<?php

namespace App\Models\Manufacturing;

use App\Models\Accounts\Account;
use App\Models\BaseModel;
use App\Models\Products\Product;
use App\Models\Products\ProductVariant;
use App\Models\Products\Unit;
use App\Models\Purchases\PurchaseProduct;
use App\Models\Branches\Branch;
use App\Models\Setups\Warehouse;

class Production extends BaseModel
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

    public function tax()
    {
        return $this->belongsTo(Account::class, 'tax_ac_id');
    }

    public function ingredients()
    {
        return $this->hasMany(ProductionIngredient::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function storeWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'store_warehouse_id');
    }

    public function stockWarehouse() // Ingredient stock warehouse
    {
        return $this->belongsTo(Warehouse::class, 'stock_warehouse_id');
    }

    public function process() // Ingredient stock warehouse
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function purchaseProduct()
    {
        return $this->belongsTo(PurchaseProduct::class, 'production_id');
    }
}
