<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchases\PurchaseProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockChain extends Model
{
    use HasFactory;

    protected $table = 'stock_chains';
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function purchaseProduct()
    {
        return $this->belongsTo(PurchaseProduct::class, 'purchase_product_id');
    }
}
