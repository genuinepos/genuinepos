<?php

namespace App\Models\Accounts;

use App\Models\BaseModel;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\PurchaseProduct;
use App\Models\Purchases\PurchaseReturn;
use App\Models\Purchases\PurchaseReturnProduct;
use App\Models\Sales\Sale;
use App\Models\Sales\SaleProduct;
use App\Models\Sales\SaleReturn;
use App\Models\Sales\SaleReturnProduct;
use App\Models\Setups\Branch;
use App\Models\StockAdjustments\StockAdjustment;

class AccountLedger extends BaseModel
{
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function voucherDescription()
    {
        return $this->belongsTo(AccountingVoucherDescription::class, 'voucher_description_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function saleProduct()
    {
        return $this->belongsTo(SaleProduct::class, 'sale_product_id');
    }

    public function salesReturn()
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id');
    }

    public function salesReturnProduct()
    {
        return $this->belongsTo(SaleReturnProduct::class, 'sale_return_product_id');
    }

    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class, 'adjustment_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function purchaseProduct()
    {
        return $this->belongsTo(PurchaseProduct::class, 'purchase_product_id');
    }

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    public function purchaseReturnProduct()
    {
        return $this->belongsTo(PurchaseReturnProduct::class, 'purchase_return_product_id');
    }
}
