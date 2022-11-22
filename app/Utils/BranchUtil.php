<?php

namespace App\Utils;

use App\Utils\Util;
use App\Models\User;
use App\Models\Account;
use App\Utils\Converter;
use App\Utils\AccountUtil;
use App\Models\CashCounter;
use App\Models\AccountBranch;
use App\Models\AccountLedger;
use Illuminate\Support\Facades\Hash;

class BranchUtil
{
    protected $accountUtil;
    protected $util;
    protected $converter;
    public function __construct(AccountUtil $accountUtil, Util $util, Converter $converter)
    {
        $this->accountUtil = $accountUtil;
        $this->util = $util;
        $this->converter = $converter;
    }

    public function addBranchDefaultAccounts($branch_id)
    {
        foreach ($this->accountUtil::creatableDefaultAccount() as $account_type => $account_array) {

            foreach ($account_array as $account_name) {

                $addAccountGetId = Account::insertGetId([
                    'name' => $account_name,
                    'account_type' => $account_type,
                    'opening_balance' => 0,
                    'balance' => 0,
                    $this->accountUtil->accountBalanceType($account_type) => 0,
                    'admin_id' => auth()->user()->id,
                ]);

                AccountBranch::insert(
                    [
                        'branch_id' => $branch_id,
                        'account_id' => $addAccountGetId,
                    ]
                );

                // Add Opening Stock Ledger
                $accountLedger = new AccountLedger();
                $accountLedger->account_id = $addAccountGetId;
                $accountLedger->voucher_type = 0;
                $accountLedger->date = date('Y-m-d H:i:s');
                $accountLedger->{$this->accountUtil->accountBalanceType($account_type)} = 0;
                $accountLedger->amount_type = $this->accountUtil->accountBalanceType($account_type);
                $accountLedger->running_balance = 0;
                $accountLedger->save();
            }
        }
    }

    public function addBranchOpeningUser($request, $branch_id)
    {
        $addUser = new User();
        $addUser->name = $request->first_name;
        $addUser->last_name = $request->last_name;
        $addUser->phone = $request->user_phone;

        $addUser->allow_login = 1;
        $addUser->username = $request->username;
        $addUser->password = Hash::make($request->password);

        $addUser->role_type = 3;
        // Assign role

        $addUser->branch_id = $branch_id;

        $addUser->save();
    }

    public function addBranchDefaultCashCounter($branch_id)
    {
        CashCounter::insert([
            'branch_id' => $branch_id,
            'counter_name' => 'Counter-1',
            'short_name' => 'CCN-1',
        ]);
    }
}
