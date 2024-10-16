<?php

namespace App\Models\Accounts;

use App\Models\User;
use App\Models\BaseModel;
use App\Models\Accounts\Bank;
use App\Models\Branches\Branch;
use App\Models\Contacts\Contact;
use App\Models\Accounts\AccountGroup;
use App\Models\Accounts\AccountLedger;
use App\Models\Accounts\BankAccessBranch;
use App\Models\Accounts\AccountOpeningBalance;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function group()
    {
        return $this->belongsTo(AccountGroup::class, 'account_group_id');
    }

    public function bankAccessBranches()
    {
        return $this->hasMany(BankAccessBranch::class, 'bank_account_id');
    }

    public function bankAccessBranch()
    {
        return $this->hasOne(BankAccessBranch::class, 'bank_account_id')->where('branch_id', auth()->user()->branch_id);
    }

    public function accountLedgers()
    {
        return $this->hasMany(AccountLedger::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function accountLedgersWithOutOpeningBalances()
    {
        return $this->hasMany(AccountLedger::class)->where('voucher_type', '!=', 0);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function accountOpeningBalance()
    {
        return $this->hasOne(AccountOpeningBalance::class, 'account_id')->where('branch_id', auth()->user()->branch_id);
    }

    public function openingBalance()
    {
        return $this->hasOne(AccountLedger::class, 'account_id')->where('voucher_type', 0)->where('branch_id', auth()->user()->branch_id);
    }
}
