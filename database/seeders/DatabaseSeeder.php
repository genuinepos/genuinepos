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
            $this->call(AddonsSeeder::class);
            $this->call(ShortMenusSeeder::class);
            $this->call(PosShortMenusSeeder::class);
        } catch (Exception $e) {
            dd($e->getMessage());
        } finally {
            echo "Operation finished." . PHP_EOL;
        }
    }
}
