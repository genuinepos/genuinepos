<?php

namespace Database\Seeders;

use Exception;
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
        try {
            echo "Seeding Default Data" . PHP_EOL;
            $this->call(GeneralSettingsSeeder::class);
            $this->call(CurrencySeeder::class);
            $this->call(AddonsSeeder::class);
            $this->call(ShortMenusSeeder::class);
            $this->call(PosShortMenusSeeder::class);
            $this->call(UserRoleSeeder::class);
            $this->call(DefaultUsersSeeder::class);
            $this->call(RolePermissionSeeder::class);
            $this->call(BarcodeSettingsSeeder::class);
            $this->call(InvoiceLayoutSeeder::class);
            $this->call(InvoiceSchemaSeeder::class);
            $this->call(UnitSeeder::class);
            $this->call(AccountSeeder::class);
            $this->call(PermissionSeeder::class);
            $this->call(ProductSeeder::class);
           
        } catch (Exception $e) {
            dd($e->getMessage());
        } finally {
            echo "Operation finished." . PHP_EOL;
        }
    }
}
