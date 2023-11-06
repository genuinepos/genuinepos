<?php

namespace App\Models\Setups;

use Illuminate\Database\Eloquent\Model;

class BranchSetting extends Model
{
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function addSaleInvoiceLayout()
    {
        return $this->belongsTo(InvoiceLayout::class, 'add_sale_invoice_layout_id');
    }

    public function posSaleInvoiceLayout()
    {
        return $this->belongsTo(InvoiceLayout::class, 'pos_sale_invoice_layout_id');
    }
}
