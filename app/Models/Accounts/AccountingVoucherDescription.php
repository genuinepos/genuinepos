<?php

namespace App\Models\Accounts;

use App\Models\Accounts\Account;
use App\Models\Setups\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounts\AccountingVoucher;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountingVoucherDescription extends Model
{
    use HasFactory;

    public function accountingVoucher()
    {
        return $this->belongsTo(AccountingVoucher::class, 'accounting_voucher_id');
    }

    public function references()
    {
        return $this->hasMany(AccountingVoucherDescriptionReference::class, 'voucher_description_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
