<?php

namespace App\Models\Setups;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\BaseModel;
use App\Models\Products\Product;
use App\Models\Setups\Warehouse;
use App\Models\Setups\InvoiceLayout;

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

    public function addSaleInvoiceLayout()
    {
        return $this->belongsTo(InvoiceLayout::class, 'add_sale_invoice_layout_id', 'id');
    }

    public function posSaleInvoiceLayout()
    {
        return $this->belongsTo(InvoiceLayout::class, 'pos_sale_invoice_layout_id');
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
}
