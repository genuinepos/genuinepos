<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ['superAdmin' => $superAdmin] = $this->getDefaultUsers();

        if (User::count() == 0) {

            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `users` AUTO_INCREMENT=1');
            $superadminRole = Role::where('name', 'superadmin')->first();
            User::create($superAdmin)->assignRole($superadminRole->name);
        }
    }

    private function getDefaultUsers(): array
    {
        $superAdmin = [
            'id' => 1,
            'prefix' => 'Mr.',
            'name' => 'Super',
            'last_name' => 'Admin',
            'emp_id' => null,
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('12345'),
            'is_belonging_an_area' => 0,
            'role_type' => 1,
            'allow_login' => 1,
            'status' => 1,
            'phone' => 'XXXXXXXXX',
            'date_of_birth' => '0000-00-00',
            'salary_type' => 'Yearly',
            'created_at' => Carbon::now(),
            'updated_at' => null,
        ];

        return [
            'superAdmin' => $superAdmin,
        ];
    }
}
