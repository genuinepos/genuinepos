<?php

namespace App\Models\StockAdjustments;

use App\Models\User;
use App\Models\BaseModel;
use App\Models\Setups\Branch;
use App\Models\Setups\Warehouse;
use App\Models\Accounts\AccountingVoucher;
use App\Models\StockAdjustments\StockAdjustmentProduct;
use App\Models\Accounts\AccountingVoucherDescriptionReference;

class StockAdjustment extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function adjustmentProducts()
    {
        return $this->hasMany(StockAdjustmentProduct::class, 'stock_adjustment_id');
    }

    public function references()
    {
        return $this->hasMany(AccountingVoucherDescriptionReference::class, 'stock_adjustment_id');
    }

    public function accountingVouchers()
    {
        return $this->hasMany(AccountingVoucher::class, 'stock_adjustment_ref_id');
    }
}
