<?php

namespace App\Models;

class ProductVariant extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'delete_in_update'];

    public function product()
    {
        return $this->belongsTo(Product::class)->select([
            'id',
            'name',
            'type',
            'tax_id',
            'brand_id',
            'category_id',
            'tax_type',
            'unit_id',
            'product_code',
            'product_cost',
            'product_cost_with_tax',
            'profit',
            'product_price',
            'offer_price',
            'quantity',
            'combo_price',
            'is_combo',
            'is_variant',
            'is_show_emi_on_pos',
            'is_manage_stock',
            'has_batch_no_expire_date',
        ]);
    }

    public function purchase_variants()
    {
        return $this->hasMany(PurchaseProduct::class, 'product_variant_id');
    }

    public function sale_variants()
    {
        return $this->hasMany(SaleProduct::class, 'product_variant_id');
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
        return $this->hasOne(PurchaseProduct::class, 'product_variant_id')->where('left_qty', '>', '0')
            ->orderBy('created_at', $ordering)->select('product_variant_id', 'net_unit_cost');
    }
}
