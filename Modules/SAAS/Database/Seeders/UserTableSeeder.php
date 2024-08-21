<?php

namespace Modules\SAAS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SAAS\Entities\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exists = User::where('id', 1)->first();
        if (!isset($exists)) {
            User::create([
                'id' => 1,
                'name' => 'Mr. Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'phone' => 'XXXXXXXXXXX',
                'photo' => null,
                'address' => null,
                'language' => 'en',
                'currency' => 'USD',
            ]);
        }
    }
}
