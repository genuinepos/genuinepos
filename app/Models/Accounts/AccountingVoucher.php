<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Accounts\AccountingVoucherDescription;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountingVoucher extends Model
{
    use HasFactory;

    public function voucherDescriptions()
    {
        return $this->hasMany(AccountingVoucherDescription::class, 'accounting_voucher_id');
    }
}
