<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Hrm\Payroll;
use App\Models\SalePayment;
use App\Models\AdminAndUser;
use App\Models\MoneyReceipt;
use App\Models\ExpansePayment;
use App\Models\PurchasePayment;
use App\Models\Hrm\PayrollPayment;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function sender_account()
    {
        return $this->belongsTo(Account::class, 'sender_account_id');
    }

    public function receiver_account()
    {
        return $this->belongsTo(Account::class, 'receiver_account_id');
    }

    public function sale_payment()
    {
        return $this->belongsTo(SalePayment::class, 'sale_payment_id');
    }

    public function purchase_payment()
    {
        return $this->belongsTo(PurchasePayment::class, 'purchase_payment_id');
    }

    public function expanse_payment()
    {
        return $this->belongsTo(ExpansePayment::class, 'expanse_payment_id');
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    public function payroll_payment()
    {
        return $this->belongsTo(PayrollPayment::class, 'payroll_payment_id');
    }

    public function money_receipt()
    {
        return $this->belongsTo(MoneyReceipt::class, 'money_receipt_id');
    }

    public function admin()
    {
        return $this->belongsTo(AdminAndUser::class, 'admin_id');
    }
}
