<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GeneralSettingsSeeder::class); // 1
        $this->call(CurrencySeeder::class); // 2
        $this->call(ShortMenusSeeder::class); // 3
        $this->call(RolePermissionSeeder::class); // 5
        // $this->call(DefaultUsersSeeder::class); // 6
        // $this->call(UserRoleSeeder::class); // 7
        $this->call(UnitSeeder::class); // 8
        $this->call(BarcodeSettingsSeeder::class); // 9
        $this->call(InvoiceLayoutSeeder::class); // 10
        // $this->call(InvoiceSchemaSeeder::class); // 11
        $this->call(AccountGroupSeeder::class); // 12A
        $this->call(AccountSeeder::class); // 12B
        $this->call(CashCounterSeeder::class); // 13
        $this->call(PaymentMethodSeeder::class); // 14
        // $this->call(ProductSeeder::class); // 15
    }
}
