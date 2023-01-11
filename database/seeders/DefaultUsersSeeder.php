<?php

namespace Database\Seeders;

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
        list('superAdmin' => $superAdmin, 'admin' => $admin, 'testUser' => $testUser) = $this->getDefaultUsers();
        if(User::count() == 0) {
            \DB::statement('ALTER TABLE `users` AUTO_INCREMENT=1');
            User::create($superAdmin);
            User::create($admin);
            User::create($testUser);
        }
    }

    private function getDefaultUsers() : array
    {
        $superAdmin = [
            "id" => 1,
            "prefix" => "Md",
            "name" => "Super",
            "last_name" => "Admin",
            "emp_id" => null,
            "username" => "superadmin",
            "email" => "admin@gmail.com",
            "email_verified_at" => "2023-01-11 11:35:53",
            "shift_id" => null,
            "role_type" => 1,
            "allow_login" => 1,
            "branch_id" => null,
            "status" => 1,
            "sales_commission_percent" => "00.00",
            "max_sales_discount_percent" => "5.00",
            "phone" => "01700000000",
            "date_of_birth" => "0000-00-00",
            "gender" => "Male",
            "marital_status" => "Married",
            "blood_group" => "a+",
            "photo" => "default.png",
            "facebook_link" => null,
            "twitter_link" => null,
            "instagram_link" => null,
            "social_media_1" => null,
            "social_media_2" => null,
            "custom_field_1" => null,
            "custom_field_2" => null,
            "guardian_name" => null,
            "id_proof_name" => null,
            "id_proof_number" => null,
            "permanent_address" => null,
            "current_address" => null,
            "bank_ac_holder_name" => "Businesss",
            "bank_ac_no" => "012441200152",
            "bank_name" => "Sonali Bank",
            "bank_identifier_code" => "0000000000000000",
            "bank_branch" => "Uttara, Dhaka",
            "tax_payer_id" => "5222222215310",
            "language" => "en",
            "department_id" => null,
            "designation_id" => null,
            "salary" => "200000000.00",
            "salary_type" => "Yearly",
            "created_at" => "2021-04-07T07:04:03.000000Z",
            "updated_at" => "2022-12-31T10:36:37.000000Z",
        ];

        $admin = [
            "id" => 2,
            "prefix" => null,
            "name" => "admin",
            "last_name" => null,
            "emp_id" => null,
            "username" => "admin",
            "email" => "admin@example.com",
            "email_verified_at" => null,
            "shift_id" => null,
            "role_type" => 1,
            "allow_login" => 1,
            "branch_id" => null,
            "status" => 0,
            "sales_commission_percent" => "0.00",
            "max_sales_discount_percent" => "0.00",
            "phone" => "0121015420",
            "date_of_birth" => null,
            "gender" => null,
            "marital_status" => null,
            "blood_group" => null,
            "photo" => "default.png",
            "facebook_link" => null,
            "twitter_link" => null,
            "instagram_link" => null,
            "social_media_1" => null,
            "social_media_2" => null,
            "custom_field_1" => null,
            "custom_field_2" => null,
            "guardian_name" => null,
            "id_proof_name" => null,
            "id_proof_number" => null,
            "permanent_address" => null,
            "current_address" => null,
            "bank_ac_holder_name" => null,
            "bank_ac_no" => null,
            "bank_name" => null,
            "bank_identifier_code" => null,
            "bank_branch" => null,
            "tax_payer_id" => null,
            "language" => null,
            "department_id" => null,
            "designation_id" => null,
            "salary" => "510121.00",
            "salary_type" => "Monthly",
            "created_at" => "2022-11-23T07:00:57.000000Z",
            "updated_at" => "2022-12-30T06:39:15.000000Z",
        ];

        $testUser = [
            "id" => 3,
            "prefix" => null,
            "name" => "Test User",
            "last_name" => null,
            "emp_id" => null,
            "username" => "test",
            "email" => "test@test.com",
            "email_verified_at" => null,
            "shift_id" => null,
            "role_type" => 2,
            "allow_login" => 1,
            "branch_id" => null,
            "status" => 0,
            "sales_commission_percent" => "0.00",
            "max_sales_discount_percent" => "0.00",
            "phone" => "0124512015",
            "date_of_birth" => null,
            "gender" => null,
            "marital_status" => null,
            "blood_group" => null,
            "photo" => "default.png",
            "facebook_link" => null,
            "twitter_link" => null,
            "instagram_link" => null,
            "social_media_1" => null,
            "social_media_2" => null,
            "custom_field_1" => null,
            "custom_field_2" => null,
            "guardian_name" => null,
            "id_proof_name" => null,
            "id_proof_number" => null,
            "permanent_address" => null,
            "current_address" => null,
            "bank_ac_holder_name" => null,
            "bank_ac_no" => null,
            "bank_name" => null,
            "bank_identifier_code" => null,
            "bank_branch" => null,
            "tax_payer_id" => null,
            "language" => null,
            "department_id" => null,
            "designation_id" => null,
            "salary" => "20000.00",
            "salary_type" => "Monthly",
            "created_at" => "2022-12-30T06:17:17.000000Z",
            "updated_at" => "2022-12-30T06:38:02.000000Z",
        ];

        return [
            'superAdmin' => $superAdmin,
            'admin' => $admin,
            'testUser' => $testUser,
        ];
    }
}
