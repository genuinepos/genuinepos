<?php

namespace App\Models\Sales;

use App\Models\User;
use App\Models\BaseModel;
use App\Models\Sales\Sale;
use App\Models\Branches\Branch;
use App\Models\Accounts\Account;
use App\Models\Accounts\AccountingVoucher;
use App\Models\Accounts\AccountingVoucherDescriptionReference;
use App\Models\Services\JobCard;

class Sale extends BaseModel
{
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class, 'sale_id');
    }

    public function salesAccount()
    {
        return $this->belongsTo(Account::class, 'sale_account_id');
    }

    public function customer()
    {
        return $this->belongsTo(Account::class, 'customer_account_id');
    }

    public function salesOrder()
    {
        return $this->belongsTo(Sale::class, 'sales_order_id');
    }

    public function saleReturn()
    {
        return $this->hasOne(SaleReturn::class, 'sale_id');
    }

    public function references()
    {
        return $this->hasMany(AccountingVoucherDescriptionReference::class, 'sale_id');
    }

    public function accountingVouchers()
    {
        return $this->hasMany(AccountingVoucher::class, 'sale_ref_id');
    }

    public function jobCard()
    {
        return $this->hasOne(JobCard::class, 'sale_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
