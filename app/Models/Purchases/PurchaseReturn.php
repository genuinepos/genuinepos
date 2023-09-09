<?php

namespace App\Models\Purchases;

use App\Models\BaseModel;
use App\Models\Setups\Branch;
use App\Models\Accounts\Account;
use App\Models\Setups\Warehouse;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\PurchaseReturnProduct;

class PurchaseReturn extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Account::class, 'supplier_account_id');
    }

    public function purchaseReturnProducts()
    {
        return $this->hasMany(PurchaseReturnProduct::class);
    }
}
