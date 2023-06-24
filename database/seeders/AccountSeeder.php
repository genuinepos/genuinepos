<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountBranch;
use App\Models\AccountLedger;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $accounts = array(
        //     array('account_type' => '7','name' => 'Office Expense','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '7','name' => 'Cartage','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '7','name' => 'Buy Goods','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '8','name' => 'Advertisement Expenses','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '8','name' => 'Rent Paid','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '9','name' => 'Current Asset','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '10','name' => 'Current Liability','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '10','name' => 'Salary Payable','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '10','name' => 'Tax Deducted Payable','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '13','name' => 'Loan Liabilities','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '14','name' => 'Loan&Advances','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '14','name' => 'Advance Salary','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '15','name' => 'Furniture','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '15','name' => 'Vehicle','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '22','name' => 'Stock Adjustment','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '23','name' => 'Production','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '24','name' => 'Income','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '24','name' => 'Discount On Purchase','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '24','name' => 'Discount Received','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '25','name' => 'Interest Received','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '26','name' => 'Capital','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '1','name' => 'Cash','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '3412045421.00','credit' => '3664588051.50','balance' => '-252542630.50','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => '2022-12-26 17:08:09','branch_id' => NULL),
        //     array('account_type' => '3','name' => 'Purchase','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '525.50','credit' => '0.00','balance' => '525.50','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => '2022-11-24 11:23:19','branch_id' => NULL),
        //     array('account_type' => '4','name' => 'Purchase Return','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '5','name' => 'Sales','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '200.00','balance' => '200.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => '2023-01-02 17:58:33','branch_id' => NULL),
        //     array('account_type' => '6','name' => 'Sales Return','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '7','name' => 'Expense','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => '2023-01-03 10:50:41','branch_id' => NULL),
        //     array('account_type' => '7','name' => 'Office Expense','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '1010.50','credit' => '0.00','balance' => '1010.50','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => '2022-11-22 16:01:10','branch_id' => NULL),
        //     array('account_type' => '7','name' => 'Cartage','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '7','name' => 'Buy Goods','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '8','name' => 'Advertisement Expenses','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '8','name' => 'Rent Paid','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '9','name' => 'Current Asset','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '10','name' => 'Current Liability','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '52541120.00','credit' => '0.00','balance' => '52541120.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => '2022-11-22 16:07:41','branch_id' => NULL),
        //     array('account_type' => '10','name' => 'Salary Payable','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '10','name' => 'Tax Deducted Payable','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '13','name' => 'Loan Liabilities','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '14','name' => 'Loan&Advances','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '200000000.00','credit' => '0.00','balance' => '200000000.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => '2022-11-21 13:29:21','branch_id' => NULL),
        //     array('account_type' => '14','name' => 'Advance Salary','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '15','name' => 'Furniture','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '15','name' => 'Vehicle','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '22','name' => 'Stock Adjustment','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '100021.00','balance' => '100021.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => '2022-11-29 10:26:24','branch_id' => NULL),
        //     array('account_type' => '23','name' => 'Production','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '24','name' => 'Income','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '24','name' => 'Discount On Purchase','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '24','name' => 'Discount Received','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '25','name' => 'Interest Received','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '26','name' => 'Capital','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '1','name' => 'Cash','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '3','name' => 'Purchase','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '4','name' => 'Purchase Return','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '24','name' => 'Discount On Purchase','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '24','name' => 'Discount Received','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '25','name' => 'Interest Received','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL),
        //     array('account_type' => '26','name' => 'Capital','account_number' => NULL,'bank_id' => NULL,'opening_balance' => '0.00','debit' => '0.00','credit' => '0.00','balance' => '0.00','remark' => NULL,'status' => '1','admin_id' => '2','created_at' => NULL,'updated_at' => NULL,'branch_id' => NULL)
        //   );

        Schema::disableForeignKeyConstraints();
        if (Account::count() == 0) {
            Account::truncate();
            DB::statement('ALTER TABLE accounts AUTO_INCREMENT=1');
        }
        foreach ($this->creatableDefaultAccount() as $account_type => $account_array) {

            foreach ($account_array as $account_name) {

                $addAccountGetId = Account::insertGetId([
                    'name' => $account_name,
                    'account_type' => $account_type,
                    'opening_balance' => 0,
                    'balance' => 0,
                    $this->accountBalanceType($account_type) => 0,
                    'admin_id' => auth()->user()?->id ?? null,
                ]);

                AccountBranch::insert(
                    [
                        'branch_id' => null,
                        'account_id' => $addAccountGetId,
                    ]
                );

                // Add Opening Stock Ledger
                $accountLedger = new AccountLedger();
                $accountLedger->account_id = $addAccountGetId;
                $accountLedger->voucher_type = 0;
                $accountLedger->date = date('Y-m-d H:i:s');
                $accountLedger->{$this->accountBalanceType($account_type)} = 0;
                $accountLedger->amount_type = $this->accountBalanceType($account_type);
                $accountLedger->running_balance = 0;
                $accountLedger->save();
            }
        }

        // DB::table('accounts')->insert($accounts);
    }

    public function creatableDefaultAccount()
    {
        return [
            1 => ['Cash'],
            3 => ['Purchase Ledger'],
            4 => ['Purchase Return'],
            5 => ['Sales'],
            6 => ['Sales Return'],
            7 => ['Expense', 'Office Expense', 'Cartage', 'Buy Goods'],
            8 => ['Advertisement Expenses', 'Rent Paid'],
            9 => ['Current Asset'],
            10 => ['Current Liability', 'Salary Payable', 'Tax Deducted Payable'],
            13 => ['Loan Liabilities'],
            14 => ['Loan&Advances', 'Advance Salary'],
            15 => ['Furniture', 'Vehicle'],
            22 => ['Stock Adjustment'],
            23 => ['Production'],
            24 => ['Income', 'Discount On Purchase', 'Discount Received'],
            25 => ['Interest Received'],
            26 => ['Capital'],
            // 26 => 'Profit & Loss A/C',
        ];
    }

    public function accountBalanceType($balance_type)
    {
        $data = [
            1 => 'debit', 2 => 'debit', 3 => 'debit', 4 => 'credit', 5 => 'credit', 6 => 'debit', 7 => 'debit', 8 => 'debit', 9 => 'debit', 10 => 'debit', 11 => 'debit', 12 => 'credit', 13 => 'credit', 14 => 'debit', 15 => 'debit', 16 => 'debit', 17 => 'debit', 18 => 'credit', 19 => 'debit', 20 => 'debit', 21 => 'debit', 22 => 'credit', 23 => 'debit', 24 => 'credit', 25 => 'credit', 26 => 'credit',
        ];

        return $data[$balance_type];
    }
}
