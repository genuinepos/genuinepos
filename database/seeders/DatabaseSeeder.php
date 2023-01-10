<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

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
            Schema::disableForeignKeyConstraints();
            $files = scandir(app_path('Models'));
            // $files = [
            //     '1',
            //     '2',
            //     'User.php',
            // ];
            for ($i = 2; $i < count($files); $i++) {
                $val = substr($files[$i], 0, -4);
                // echo "App\Models\\$val" . PHP_EOL;
                "App\Models\\$val"::factory()?->count(20)->create() ?? "$val Failed\n";
            }
            Schema::enableForeignKeyConstraints();
            // $this->call(GeneralSettingsSeeder::class);
            // $this->call(CurrencySeeder::class);
            // $this->call(ShortMenusSeeder::class);
            // $this->call(PosShortMenusSeeder::class);
            // $this->call(UserRoleSeeder::class);
            // $this->call(DefaultUsersSeeder::class);
            // $this->call(RolePermissionSeeder::class);
            // $this->call(BarcodeSettingsSeeder::class);
            // $this->call(InvoiceLayoutSeeder::class);
            // $this->call(InvoiceSchemaSeeder::class);
            // $this->call(UnitSeeder::class);
            // $this->call(AccountSeeder::class);
            // $this->call(PermissionSeeder::class);
            // $this->call(ProductSeeder::class);

        } catch (Exception $e) {
            dd($e->getMessage());
        } finally {
            echo "Operation finished." . PHP_EOL;
        }
    }
}
