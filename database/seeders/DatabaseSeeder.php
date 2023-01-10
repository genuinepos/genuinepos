<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
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
                // App\Models\User.php
                // App\Models\Hrm\Payroll.php

                $s1 = substr($files[$i], 0, -4);
                $s2 = \explode('\\', $s1);
                if(count($s2) == 4) {
                    $subDir = $s2[count($s2) - 2];
                    $val = $s2[count($s2) - 1];
                    $model = \app_path("Models") . \DIRECTORY_SEPARATOR . $subDir . \DIRECTORY_SEPARATOR . "{$val}.php";
                } else {
                    $val = $s2[count($s2) - 1];
                    $model = \app_path('Models') . \DIRECTORY_SEPARATOR . "{$val}.php";
                }
                echo "$model === ";
                $modelExists = File::exists($model);
                $factoryFile = \database_path('factories') . \DIRECTORY_SEPARATOR . "{$val}Factory.php";
                echo "$factoryFile\n";
                $factoryExists = File::exists($factoryFile);
                // dd($modelExists);
                // dd($factoryExists);
                // echo "App\Models\\$val" . PHP_EOL;
                // if($modelExists && $factoryExists) {
                //     "App\Models\\$val"::factory()?->count(20)->create() ?? "$val Failed\n";
                // }
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
