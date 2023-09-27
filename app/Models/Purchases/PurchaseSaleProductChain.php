<?php

namespace App\Models\Purchases;

use App\Models\BaseModel;
use App\Models\Purchases\PurchaseProduct;

class PurchaseSaleProductChain extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function purchaseProduct()
    {
        return $this->belongsTo(PurchaseProduct::class, 'purchase_product_id');
    }
}
