<?php

namespace App\Models\Products;

use App\Models\ComboProduct;
use App\Models\Accounts\Account;
use App\Models\Sales\SaleProduct;
use App\Models\Products\ProductUnit;
use App\Models\Manufacturing\Process;
use App\Enums\ProductLedgerVoucherType;
use Illuminate\Database\Eloquent\Model;
use App\Models\Manufacturing\Production;
use App\Models\Purchases\PurchaseProduct;
use App\Models\Purchases\PurchaseOrderProduct;
use App\Models\Manufacturing\ProcessIngredient;
use App\Models\TransferStocks\TransferStockProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function priceGroups()
    {
        return $this->hasMany(PriceGroupProduct::class, 'product_id');
    }

    public function purchasedVariants()
    {
        return $this->hasMany(ProductVariant::class, 'variant_id')->where('is_purchased', 1);
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
        return $this->hasMany(TransferStockProduct::class, 'product_id');
    }

    public function processIngredients()
    {
        return $this->hasMany(ProcessIngredient::class, 'product_id');
    }

    public function purchaseOrderedProducts()
    {
        return $this->hasMany(PurchaseOrderProduct::class);
    }

    public function transferWarehouseToBranchProducts()
    {
        return $this->hasMany(TransferStockProduct::class, 'product_id');
    }

    public function transferBranchToWarehouseProducts()
    {
        return $this->hasMany(TransferStockProduct::class, 'product_id');
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
        return $this->belongsTo(Account::class, 'tax_ac_id')->select(['id', 'name', 'tax_percent']);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id')->select('id', 'name', 'code_name');
    }

    public function productUnits()
    {
        return $this->hasMany(ProductUnit::class, 'product_id')->whereNull('variant_id');
    }

    public function warranty()
    {
        return $this->belongsTo(Warranty::class, 'warranty_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)->select(['id', 'name']);
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
            ->where('branch_id', auth()->user()->branch_id)
            ->orderBy('created_at', $ordering)->select('product_id', 'net_unit_cost');
    }

    public function productBranchStock()
    {
        return $this->hasOne(ProductStock::class, 'product_id')->where('branch_id', auth()->user()->branch_id)
            ->select('id', 'branch_id', 'product_id', 'stock', 'all_stock');
    }

    public function productAccessBranches()
    {
        return $this->hasMany(ProductAccessBranch::class);
    }

    public function productAccessBranch($branchId = null)
    {
        // $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
        return $this->hasOne(ProductAccessBranch::class)->where('branch_id', $branchId);
    }

    public function ledgerEntries()
    {
        return $this->hasMany(ProductLedger::class, 'product_id')->where('voucher_type', '!=', ProductLedgerVoucherType::OpeningStock->value);
    }

    public function ownBranchAllStocks()
    {
        return $this->hasMany(ProductStock::class, 'product_id')->where('branch_id', auth()->user()->branch_id);
    }
}
