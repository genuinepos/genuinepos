<?php

namespace App\Models\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ComboProduct;
use App\Models\Manufacturing\Process;
use App\Models\Manufacturing\ProcessIngredient;
use App\Models\Manufacturing\Production;
use App\Models\ProductBranch;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductWarehouse;
use App\Models\PurchaseOrderProduct;
use App\Models\PurchaseProduct;
use App\Models\SaleProduct;
use App\Models\Tax;
use App\Models\TransferStockBranchToBranchProducts;
use App\Models\TransferStockToBranchProduct;
use App\Models\TransferStockToWarehouseProduct;
use App\Models\Unit;
use App\Models\Warranty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function ComboProducts()
    {
        return $this->hasMany(ComboProduct::class, 'product_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function purchasedVariants()
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

    public function purchasedProducts()
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

    public function purchaseOrderedProducts()
    {
        return $this->hasMany(PurchaseOrderProduct::class);
    }

    public function transferToBranchProducts()
    {
        return $this->hasMany(TransferStockToBranchProduct::class);
    }

    public function transferToWarehouseProducts()
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
        return $this->belongsTo(Tax::class, 'tax_id')->select(['id', 'tax_name', 'tax_percent']);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id')->select('id', 'name', 'code_name');
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

        $stockAccountingMethod = $generalSettings['business__stock_accounting_method'];

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
}
