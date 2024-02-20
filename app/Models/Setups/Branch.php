<?php

namespace App\Models\Setups;

use App\Models\Sales\Sale;
use App\Models\Purchases\Purchase;
use App\Models\BaseModel;
use App\Models\InvoiceSchema;
use App\Models\Products\Product;
use App\Models\Setups\Warehouse;
use App\Models\Setups\BranchSetting;
use App\Models\Products\ProductOpeningStock;
use App\Models\Subscriptions\ShopExpireDateHistory;

class Branch extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'expire_at' => 'date',
    ];

    public function shopExpireDateHistory()
    {
        return $this->belongsTo(ShopExpireDateHistory::class, 'shop_expire_date_history_id');
    }

    public function invoiceSchema()
    {
        return $this->belongsTo(InvoiceSchema::class, 'invoice_schema_id');
    }

    public function childBranches()
    {
        return $this->hasMany(Branch::class, 'parent_branch_id');
    }

    public function parentBranch()
    {
        return $this->belongsTo(Branch::class, 'parent_branch_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function addSaleInvoiceLayout()
    {
        return $this->hasOne(BranchSetting::class, 'branch_id')->where('add_sale_invoice_layout_id', '!=', null);
    }

    public function posSaleInvoiceLayout()
    {
        return $this->hasOne(BranchSetting::class, 'branch_id')->where('pos_sale_invoice_layout_id', '!=', null);
    }

    public function openingStockProduct()
    {
        return $this->hasOne(ProductOpeningStock::class, 'branch_id')
            ->where('warehouse_id', null);
    }
}
