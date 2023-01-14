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
        $this->call(PosShortMenusSeeder::class); // 4
        $this->call(RolePermissionSeeder::class); // 5
        $this->call(DefaultUsersSeeder::class); // 6
        $this->call(UserRoleSeeder::class); // 7
        $this->call(UnitSeeder::class); // 8
        $this->call(BarcodeSettingsSeeder::class); // 9
        $this->call(InvoiceLayoutSeeder::class); // 10
        $this->call(InvoiceSchemaSeeder::class); // 11
        $this->call(AccountSeeder::class); // 12
        $this->call(ProductSeeder::class); // 13
    }
}
