<?php

namespace App\Models;

use App\Models\SaleProduct;
use App\Models\PurchaseProduct;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at', 'delete_in_update'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->select([
            'id', 
            'name',
            'type',
            'tax_id', 
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
}
