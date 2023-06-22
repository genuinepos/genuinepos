<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UnitSeeder;
use Database\Seeders\AccountSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\UserRoleSeeder;
use Database\Seeders\ShortMenusSeeder;
use Database\Seeders\CashCounterSeeder;
use Database\Seeders\DefaultUsersSeeder;
use Database\Seeders\InvoiceLayoutSeeder;
use Database\Seeders\InvoiceSchemaSeeder;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\PosShortMenusSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\BarcodeSettingsSeeder;
use Database\Seeders\GeneralSettingsSeeder;

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
        $this->call(CashCounterSeeder::class); // 13
        $this->call(PaymentMethodSeeder::class); // 14
        // $this->call(ProductSeeder::class); // 15
    }
}
