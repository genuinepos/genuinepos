<?php

namespace App\Models;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use Illuminate\Database\Eloquent\Model;

class SupplierLedger extends Model
{
    protected $guarded = [];
    protected $hidden = ['updated_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function purchase_payment()
    {
        return $this->belongsTo(PurchasePayment::class, 'purchase_payment_id');
    }
}
