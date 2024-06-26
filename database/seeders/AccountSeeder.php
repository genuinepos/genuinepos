<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accounts\Account;
use Illuminate\Support\Facades\DB;
use App\Models\Accounts\AccountGroup;
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
        $cashInHand = DB::table('account_groups')->where('sub_sub_group_number', 2)->first();
        $directExpenseGroup = DB::table('account_groups')->where('sub_group_number', 10)->first();
        $directIncomeGroup = DB::table('account_groups')->where('sub_group_number', 13)->first();
        $salesAccountGroup = DB::table('account_groups')->where('sub_group_number', 15)->first();
        $purchaseAccountGroup = DB::table('account_groups')->where('sub_group_number', 12)->first();
        $accountReceivablesAccountGroup = DB::table('account_groups')->where('sub_sub_group_number', 6)->first();
        // $suspenseAccountGroup = AccountGroup::where('sub_group_number', 9)->first();
        $capitalAccountGroup = DB::table('account_groups')->where('sub_group_number', 6)->first();
        $dutiesAndTaxAccountGroup = DB::table('account_groups')->where('sub_sub_group_number', 8)->first();

        $accounts = [
            ['account_group_id' => $cashInHand->id, 'name' => 'Cash', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => '1', 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'is_walk_in_customer' => 0, 'is_global' => 0, 'created_at' => '2023-08-04 17:33:01', 'updated_at' => '2023-08-04 17:33:01', 'branch_id' => null],

            ['account_group_id' => $salesAccountGroup->id, 'name' => 'Sales Ledger Account', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => '1', 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'is_walk_in_customer' => 0, 'is_global' => 0, 'created_at' => '2023-08-06 12:02:13', 'updated_at' => '2023-08-06 12:02:13', 'branch_id' => null],

            ['account_group_id' => $dutiesAndTaxAccountGroup->id, 'name' => 'Tax@5%', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '5.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'is_walk_in_customer' => 0, 'is_global' => 1, 'created_at' => '2023-08-06 16:59:55', 'updated_at' => '2023-08-06 16:59:55', 'branch_id' => null],

            ['account_group_id' => $dutiesAndTaxAccountGroup->id, 'name' => 'Tax@8%', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '8.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'is_walk_in_customer' => 0, 'is_global' => 1, 'created_at' => '2023-08-06 17:00:18', 'updated_at' => '2023-08-06 17:00:18', 'branch_id' => null],

            ['account_group_id' => $purchaseAccountGroup->id, 'name' => 'Purchase Ledger Account', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'is_walk_in_customer' => 0, 'is_global' => 0, 'created_at' => '2023-08-08 18:09:48', 'updated_at' => '2023-08-08 18:09:48', 'branch_id' => null],

            ['account_group_id' => $directExpenseGroup->id, 'name' => 'Net Bill', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'is_walk_in_customer' => 0, 'is_global' => 0, 'created_at' => '2023-08-08 18:10:36', 'updated_at' => '2023-08-08 18:10:36', 'branch_id' => null],

            ['account_group_id' => $directExpenseGroup->id, 'name' => 'Electricity Bill', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'is_walk_in_customer' => 0, 'is_global' => 0, 'created_at' => '2023-08-08 18:10:53', 'updated_at' => '2023-08-08 18:10:53', 'branch_id' => null],

            ['account_group_id' => $directExpenseGroup->id, 'name' => 'Snacks Bill', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'is_walk_in_customer' => 0, 'is_global' => 0, 'created_at' => '2023-08-08 18:11:16', 'updated_at' => '2023-08-08 18:11:16', 'branch_id' => null],

            ['account_group_id' => $directExpenseGroup->id, 'name' => 'Roll Pages', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'is_walk_in_customer' => 0, 'is_global' => 0, 'created_at' => '2023-08-08 18:11:59', 'updated_at' => '2023-08-08 18:11:59', 'branch_id' => null],

            ['account_group_id' => $directIncomeGroup->id, 'name' => 'Sale Damage Goods', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'is_walk_in_customer' => 0, 'is_global' => 0, 'created_at' => '2023-08-08 18:12:33', 'updated_at' => '2023-08-08 18:12:33', 'branch_id' => null],

            ['account_group_id' => $directExpenseGroup->id, 'name' => 'Lost/Damage Stock', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'is_walk_in_customer' => 0, 'is_global' => 0, 'created_at' => '2023-08-08 18:13:13', 'updated_at' => '2023-08-08 18:13:13', 'branch_id' => null],

            ['account_group_id' => $accountReceivablesAccountGroup->id, 'name' => 'Walk-In-Customer', 'phone' => 0, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'is_walk_in_customer' => 1, 'is_global' => 0,  'created_at' => '2023-08-08 18:13:13', 'updated_at' => '2023-08-08 18:13:13', 'branch_id' => null],

            // ['account_group_id' => $suspenseAccountGroup->id, 'name' => 'Profit Loss Account', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => '1', 'is_walk_in_customer' => 0, 'is_global' => 1, 'created_at' => '2023-08-08 18:13:57', 'updated_at' => '2023-08-08 18:13:57', 'branch_id' => null],

            ['account_group_id' => $capitalAccountGroup->id, 'name' => 'Capital Account', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => '1', 'is_main_pl_account' => null, 'is_walk_in_customer' => 0, 'is_global' => 1,  'created_at' => '2023-08-08 18:14:40', 'updated_at' => '2023-08-08 18:14:40', 'branch_id' => null],
        ];

        Schema::disableForeignKeyConstraints();
        Account::insert($accounts);
        Schema::enableForeignKeyConstraints();
    }
}
