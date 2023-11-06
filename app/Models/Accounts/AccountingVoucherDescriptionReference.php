<?php

namespace App\Models\Accounts;

use App\Models\Purchases\Purchase;
use App\Models\Purchases\PurchaseReturn;
use App\Models\Sales\Sale;
use App\Models\Sales\SaleReturn;
use App\Models\StockAdjustments\StockAdjustment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingVoucherDescriptionReference extends Model
{
    use HasFactory;

    protected $table = 'voucher_description_references';

    public function voucherDescription()
    {
        return $this->belongsTo(AccountingVoucherDescription::class, 'voucher_description_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function salesReturn()
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id');
    }

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class, 'stock_adjustment_id');
    }
}
