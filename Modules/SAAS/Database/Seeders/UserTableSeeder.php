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
        User::factory([
            'name' => 'Mr. Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'phone' => fake()->phoneNumber(),
            'photo' => fake()->imageUrl(),
            'address' => fake()->address(),
            'language' => 'en',
            'currency' => 'USD',
        ])->create();
    }
}
