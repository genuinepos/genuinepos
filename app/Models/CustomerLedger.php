<?php

namespace App\Models;

use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\MoneyReceipt;
use Illuminate\Database\Eloquent\Model;

class CustomerLedger extends Model
{
    protected $guarded = [];
    protected $hidden = ['updated_at'];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function sale_payment()
    {
        return $this->belongsTo(SalePayment::class, 'sale_payment_id');
    }

    public function money_receipt()
    {
        return $this->belongsTo(MoneyReceipt::class, 'money_receipt_id');
    }
}
