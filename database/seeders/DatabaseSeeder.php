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
            $mailSettings = '{"MAIL_MAILER":"smtp","MAIL_HOST":"smtp.gmail.com","MAIL_PORT":"587","MAIL_USERNAME":"s1@gmail.com","MAIL_PASSWORD":"speeddigit@54321","MAIL_ENCRYPTION":"tls","MAIL_FROM_ADDRESS":"s1@gmail.com","MAIL_FROM_NAME":"SpeedDigit","MAIL_ACTIVE":true}';

        } catch (Exception $e) {
            dd($e->getMessage());
        } finally {
            echo "Operation finished." . PHP_EOL;
        }
    }
}
