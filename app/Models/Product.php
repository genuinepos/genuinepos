<?php

namespace App\Models;

use App\Models\Accounts\Account;
use App\Models\Manufacturing\Process;
use App\Models\Manufacturing\ProcessIngredient;
use App\Models\Manufacturing\Production;
use App\Models\Products\Brand;
use App\Models\Products\ProductAccessBranch;
use App\Models\Products\Unit;
use App\Models\Products\Warranty;

class Product extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function ComboProducts()
    {
        return $this->hasMany(ComboProduct::class, 'product_id');
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function productPurchasedVariants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id')->where('is_purchased', 1);
    }

    public function productBranches()
    {
        return $this->hasMany(ProductBranch::class);
    }

    public function productWarehouses()
    {
        return $this->hasMany(ProductWarehouse::class);
    }

    public function purchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::class, 'product_id')
            ->where('product_id', null)
            ->where('opening_stock_id', null)
            ->where('sale_return_product_id', null)
            ->where('transfer_branch_to_branch_product_id', null);
    }

    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class, 'product_id');
    }

    public function productions()
    {
        return $this->hasMany(Production::class, 'product_id');
    }

    public function processes()
    {
        return $this->hasMany(Process::class, 'product_id');
    }

    public function transferBranchToBranchProducts()
    {
        return $this->hasMany(TransferStockBranchToBranchProducts::class, 'product_id');
    }

    public function processIngredients()
    {
        return $this->hasMany(ProcessIngredient::class, 'product_id');
    }

    public function orderProducts()
    {
        return $this->hasMany(PurchaseOrderProduct::class);
    }

    public function transfer_to_branch_products()
    {
        return $this->hasMany(TransferStockToBranchProduct::class);
    }

    public function transfer_to_warehouse_products()
    {
        return $this->hasMany(TransferStockToWarehouseProduct::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->select(['id', 'name']);
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id', 'id')->select(['id', 'name']);
    }

    public function tax()
    {
        return $this->belongsTo(Account::class, 'tax_ac_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function warranty()
    {
        return $this->belongsTo(Warranty::class, 'warranty_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)->select(['id', 'name']);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function updateProductCost()
    {
        $generalSettings = config('generalSettings');

        $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        if ($stockAccountingMethod == 1) {
            $ordering = 'asc';
        } else {
            $ordering = 'desc';
        }

        return $this->hasOne(PurchaseProduct::class)->where('left_qty', '>', '0')
            ->orderBy('created_at', $ordering)->select('product_id', 'net_unit_cost');
    }

    public function stockLimit()
    {
        return $this->hasOne(ProductBranch::class)->where('branch_id', auth()->user()->branch_id)
            ->select('id', 'branch_id', 'product_id', 'product_quantity');
    }

    public function productAccessBranch()
    {
        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        return $this->hasOne(ProductAccessBranch::class)->where('branch_id', $ownBranchIdOrParentBranchId);
    }
}
