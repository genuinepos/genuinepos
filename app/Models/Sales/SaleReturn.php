<?php

namespace App\Models\Sales;

use App\Models\User;
use App\Models\BaseModel;
use App\Models\Sales\Sale;
use App\Models\Setups\Branch;
use App\Models\Accounts\Account;
use App\Models\Setups\Warehouse;
use App\Models\Sales\SaleReturnProduct;
use App\Models\Accounts\AccountingVoucher;
use App\Models\Accounts\AccountingVoucherDescriptionReference;

class SaleReturn extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function customer()
    {
        return $this->belongsTo(Account::class, 'customer_account_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function saleReturnProducts()
    {
        return $this->hasMany(SaleReturnProduct::class);
    }

    public function references()
    {
        return $this->hasMany(AccountingVoucherDescriptionReference::class, 'sale_return_id');
    }

    public function accountingVouchers()
    {
        return $this->hasMany(AccountingVoucher::class, 'sale_return_ref_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}