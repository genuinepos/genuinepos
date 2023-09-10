<?php

namespace App\Models\Accounts;

use App\Models\Purchases\Purchase;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounts\AccountingVoucherDescription;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountingVoucherDescriptionReference extends Model
{
    protected $table = 'voucher_description_references';
    use HasFactory;

    public function voucherDescription()
    {
        return $this->belongsTo(AccountingVoucherDescription::class, 'voucher_description_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
}
