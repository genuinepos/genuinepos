<?php

namespace App\Models\Products;

use App\Models\Products\Product;
use App\Models\Sales\SaleProduct;
use App\Models\Products\ProductStock;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchases\PurchaseProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'delete_in_update'];

    public function product()
    {
        return $this->belongsTo(Product::class)->select([
            'id',
            'name',
            'tax_ac_id',
            'tax_type',
            'unit_id',
            'product_cost',
            'product_cost_with_tax',
            'profit',
            'product_price',
            'combo_price',
            'is_combo',
            'is_for_sale',
            'is_variant',
            'is_show_emi_on_pos',
            'is_manage_stock',
            'has_batch_no_expire_date',
        ]);
    }

    public function purchaseVariants()
    {
        return $this->hasMany(PurchaseProduct::class, 'variant_id');
    }

    public function saleVariants()
    {
        return $this->hasMany(SaleProduct::class, 'variant_id');
    }

    public function productLedgers()
    {
        return $this->hasMany(ProductLedger::class, 'variant_id')->where('voucher_type', '!=', 0);
    }

    public function updateVariantCost()
    {
        $generalSettings = config('generalSettings');

        $stockAccountingMethod = $generalSettings['business__stock_accounting_method'];

        if ($stockAccountingMethod == 1) {

            $ordering = 'asc';
        } else {

            $ordering = 'desc';
        }

        return $this->hasOne(PurchaseProduct::class, 'variant_id')
            ->where('left_qty', '>', '0')
            ->where('branch_id', auth()->user()->branch_id)
            ->orderBy('created_at', $ordering)->select('variant_id', 'net_unit_cost');
    }

    public function variantBranchStock()
    {
        return $this->hasOne(ProductStock::class, 'variant_id')->where('branch_id', auth()->user()->branch_id)
            ->select('id', 'branch_id', 'product_id', 'variant_id', 'stock', 'all_stock');
    }
}
