<?php

namespace App\Models\Accounts;

use App\Models\Sales\Sale;
use App\Models\Setups\Branch;
use App\Models\Accounts\Account;
use App\Models\Products\Product;
use App\Models\Sales\SaleReturn;
use App\Models\Purchases\Purchase;
use App\Models\Products\StockIssue;
use App\Models\Products\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use App\Models\Manufacturing\Production;
use App\Models\Purchases\PurchaseReturn;
use App\Models\TransferStocks\TransferStock;
use App\Models\StockAdjustments\StockAdjustment;
use App\Models\Accounts\AccountingVoucherDescription;
use App\Models\Hrm\Payroll;

class DayBook extends Model
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

    public function salesReturn()
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id');
    }

    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class, 'adjustment_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    public function production()
    {
        return $this->belongsTo(Production::class, 'production_id');
    }

    public function transferStock()
    {
        return $this->belongsTo(TransferStock::class, 'transfer_stock_id');
    }

    public function stockIssue()
    {
        return $this->belongsTo(StockIssue::class, 'stock_issue_id');
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
