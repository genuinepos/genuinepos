<?php

namespace App\Models\Purchases;

use App\Models\Accounts\Account;
use App\Models\Accounts\AccountingVoucher;
use App\Models\Accounts\AccountingVoucherDescriptionReference;
use App\Models\BaseModel;
use App\Models\Setups\Branch;
use App\Models\Setups\Warehouse;
use App\Models\User;

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
        return $this->hasMany(PurchaseReturnProduct::class, 'purchase_return_id');
    }

    public function references()
    {
        return $this->hasMany(AccountingVoucherDescriptionReference::class, 'purchase_return_id');
    }

    public function accountingVouchers()
    {
        return $this->hasMany(AccountingVoucher::class, 'purchase_return_ref_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
