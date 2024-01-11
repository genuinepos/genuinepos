<?php

namespace Database\Seeders;

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
        ['superAdmin' => $superAdmin, 'admin' => $admin] = $this->getDefaultUsers();
        if (User::count() == 0) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `users` AUTO_INCREMENT=1');
            $superadminRole = Role::where('name', 'superadmin')->first();
            User::create($superAdmin)->assignRole($superadminRole);
            $adminRole = Role::where('name', 'admin')->first();
            User::create($admin)->assignRole($adminRole);
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
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345'),
            'email_verified_at' => '2023-01-11 11:35:53',
            'shift_id' => null,
            'role_type' => 1,
            'allow_login' => 1,
            'branch_id' => null,
            'is_belonging_an_area' => 0,
            'status' => 1,
            'sales_commission_percent' => 0,
            'max_sales_discount_percent' => 0,
            'phone' => '01700000000',
            'date_of_birth' => '0000-00-00',
            'gender' => null,
            'marital_status' => null,
            'blood_group' => null,
            'photo' => 'default.png',
            'facebook_link' => null,
            'twitter_link' => null,
            'instagram_link' => null,
            'social_media_1' => null,
            'social_media_2' => null,
            'custom_field_1' => null,
            'custom_field_2' => null,
            'guardian_name' => null,
            'id_proof_name' => null,
            'id_proof_number' => null,
            'permanent_address' => null,
            'current_address' => null,
            'bank_ac_holder_name' => null,
            'bank_ac_no' => null,
            'bank_name' => null,
            'bank_identifier_code' => null,
            'bank_branch' => null,
            'tax_payer_id' => null,
            'language' => 'en',
            'department_id' => null,
            'designation_id' => null,
            'salary' => 0,
            'salary_type' => 'Yearly',
            'created_at' => '2021-04-07T07:04:03.000000Z',
            'updated_at' => '2022-12-31T10:36:37.000000Z',
        ];

        $admin = [
            'id' => 2,
            'prefix' => 'Mr.',
            'name' => 'Admin',
            'last_name' => null,
            'emp_id' => null,
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345'),
            'email_verified_at' => null,
            'shift_id' => null,
            'role_type' => 1,
            'allow_login' => 1,
            'branch_id' => null,
            'status' => 0,
            'sales_commission_percent' => '0',
            'max_sales_discount_percent' => '0',
            'phone' => '0170000001',
            'date_of_birth' => null,
            'gender' => null,
            'marital_status' => null,
            'blood_group' => null,
            'photo' => 'default.png',
            'facebook_link' => null,
            'twitter_link' => null,
            'instagram_link' => null,
            'social_media_1' => null,
            'social_media_2' => null,
            'custom_field_1' => null,
            'custom_field_2' => null,
            'guardian_name' => null,
            'id_proof_name' => null,
            'id_proof_number' => null,
            'permanent_address' => null,
            'current_address' => null,
            'bank_ac_holder_name' => null,
            'bank_ac_no' => null,
            'bank_name' => null,
            'bank_identifier_code' => null,
            'bank_branch' => null,
            'tax_payer_id' => null,
            'language' => null,
            'department_id' => null,
            'designation_id' => null,
            'salary' => 0,
            'salary_type' => 'Monthly',
            'created_at' => '2022-11-23T07:00:57.000000Z',
            'updated_at' => '2022-12-30T06:39:15.000000Z',
        ];

        return [
            'superAdmin' => $superAdmin,
            'admin' => $admin,
        ];
    }
}
