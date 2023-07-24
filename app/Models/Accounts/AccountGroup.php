<?php

namespace App\Models\Accounts;

use App\Models\Account;
use App\Models\Accounts\AccountGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountGroup extends Model
{
    use HasFactory;

    public function childGroups()
    {
        return $this->hasMany(AccountGroup::class, 'parent_group_id');
    }

    public function subgroups()
    {
        return $this->hasMany(AccountGroup::class, 'parent_group_id')->with('subgroups');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class, 'account_group_id')->orderBy('accounts.name', 'ASC');
    }

    public function subgroupsAccounts()
    {
        return $this->hasMany(AccountGroup::class, 'parent_group_id')->with(
            [
                'accounts:id,name,phone,account_number,account_group_id',
                'subgroupsAccounts:id,name,parent_group_id',
                'subgroupsAccounts.accounts:id,name,phone,account_number,account_group_id',
            ]
        );
    }

    public function subgroupsAccountsForOthers()
    {
        return $this->hasMany(AccountGroup::class, 'parent_group_id')->with(
            [
                'accounts:id,name,phone,account_number,account_group_id',
                'accounts.accountLedgers:id,is_cash_flow,account_id,date,voucher_type,debit,credit',
                'subgroupsAccountsForOthers:id,name,parent_group_id,sub_group_number,sub_sub_group_number',
                'subgroupsAccountsForOthers.accounts:id,name,phone,account_number,account_group_id',
                'subgroupsAccountsForOthers.accounts.accountLedgers:id,is_cash_flow,account_id,date,voucher_type,debit,credit',
            ]
        );
    }

    public function parentGroup()
    {
        return $this->belongsTo(AccountGroup::class, 'parent_group_id');
    }
}
