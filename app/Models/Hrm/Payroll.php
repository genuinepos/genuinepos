<?php

namespace App\Models\Hrm;

use App\Models\User;
use App\Models\BaseModel;
use App\Models\Setups\Branch;
use App\Models\Accounts\Account;
use App\Models\Accounts\AccountingVoucher;
use App\Models\Accounts\AccountingVoucherDescriptionReference;

class Payroll extends BaseModel
{
    protected $table = 'hrm_payrolls';

    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function payments()
    {
        return $this->hasMany(AccountingVoucher::class, 'payroll_ref_id');
    }

    public function allowances()
    {
        return $this->hasMany(PayrollAllowance::class, 'payroll_id');
    }

    public function deductions()
    {
        return $this->hasMany(PayrollDeduction::class, 'payroll_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function expenseAccount()
    {
        return $this->belongsTo(Account::class, 'expense_account_id');
    }

    public function references()
    {
        return $this->hasMany(AccountingVoucherDescriptionReference::class, 'payroll_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }
}
