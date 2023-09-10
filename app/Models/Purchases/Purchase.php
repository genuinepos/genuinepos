<?php

namespace App\Models\Purchases;

use App\Models\User;
use App\Models\BaseModel;
use App\Models\Setups\Branch;
use App\Models\Accounts\Account;
use App\Models\Accounts\AccountingVoucher;
use App\Models\Setups\Warehouse;
use App\Models\Accounts\AccountingVoucherDescriptionReference;

class Purchase extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $table = 'purchases';

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function purchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id');
    }

    public function purchaseOrderProducts()
    {
        return $this->hasMany(PurchaseOrderProduct::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Account::class, 'supplier_account_id');
    }

    public function purchaseAccount()
    {
        return $this->belongsTo(Account::class, 'purchase_account_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function purchaseReturn()
    {
        return $this->hasOne(PurchaseReturn::class, 'purchase_id');
    }

    public function references()
    {
        return $this->hasMany(AccountingVoucherDescriptionReference::class, 'purchase_id');
    }

    // public function purchase_payments()
    // {
    //     return $this->hasMany(PurchasePayment::class);
    // }

    // public function ledger()
    // {
    //     return $this->hasOne(SupplierLedger::class);
    // }

    public function accountingVouchers()
    {
        return $this->hasMany(AccountingVoucher::class, 'purchase_ref_id');
    }

    public function transferToWarehouse()
    {
        return $this->hasMany(TransferStockToWarehouse::class);
    }

    public function transferToBranch()
    {
        return $this->hasMany(TransferStockToBranch::class);
    }
}
