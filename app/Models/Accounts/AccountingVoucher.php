<?php

namespace App\Models\Accounts;

use App\Models\Hrm\Payroll;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\PurchaseReturn;
use App\Models\Sales\Sale;
use App\Models\Sales\SaleReturn;
use App\Models\Setups\Branch;
use App\Models\StockAdjustments\StockAdjustment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingVoucher extends Model
{
    use HasFactory;

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function voucherDescriptions()
    {
        return $this->hasMany(AccountingVoucherDescription::class, 'accounting_voucher_id');
    }

    public function voucherDebitDescription()
    {
        return $this->hasOne(AccountingVoucherDescription::class, 'accounting_voucher_id')->where('amount_type', 'dr');
    }

    public function voucherDebitDescriptions()
    {
        return $this->hasMany(AccountingVoucherDescription::class, 'accounting_voucher_id')->where('amount_type', 'dr');
    }

    public function voucherCreditDescription()
    {
        return $this->hasOne(AccountingVoucherDescription::class, 'accounting_voucher_id')->where('amount_type', 'cr');
    }

    public function saleRef()
    {
        return $this->belongsTo(Sale::class, 'sale_ref_id');
    }

    public function purchaseRef()
    {
        return $this->belongsTo(Purchase::class, 'purchase_ref_id');
    }

    public function salesReturnRef()
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_ref_id');
    }

    public function purchaseReturnRef()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_ref_id');
    }

    public function stockAdjustmentRef()
    {
        return $this->belongsTo(StockAdjustment::class, 'stock_adjustment_ref_id');
    }

    public function payrollRef()
    {
        return $this->belongsTo(Payroll::class, 'payroll_ref_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
