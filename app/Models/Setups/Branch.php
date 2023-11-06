<?php

namespace App\Models\Setups;

use App\Models\BaseModel;
use App\Models\Products\Product;
use App\Models\Products\ProductOpeningStock;
use App\Models\Purchase;
use App\Models\Sale;

class Branch extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

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

    public function branchSetting()
    {
        return $this->hasOne(BranchSetting::class, 'branch_id');
    }

    public function openingStockProduct()
    {
        return $this->hasOne(ProductOpeningStock::class, 'branch_id')
            ->where('warehouse_id', null);
    }
}
