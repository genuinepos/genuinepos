<?php

namespace Modules\SAAS\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $admin = [
            'id' => 1,
            'prefix' => 'Md.',
            'name' => 'Super',
            'last_name' => 'Admin',
            'emp_id' => null,
            'username' => 'superadmin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => '2023-01-11 11:35:53',
            'shift_id' => null,
            'role_type' => 1,
            'allow_login' => 1,
            'branch_id' => null,
            'status' => 1,
            'sales_commission_percent' => '00.00',
            'max_sales_discount_percent' => '5.00',
            'phone' => '01700000000',
            'date_of_birth' => '0000-00-00',
            'gender' => 'Male',
            'marital_status' => 'Married',
            'blood_group' => 'a+',
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
            'bank_ac_holder_name' => 'Businesss',
            'bank_ac_no' => '012441200152',
            'bank_name' => 'Sonali Bank',
            'bank_identifier_code' => '0000000000000000',
            'bank_branch' => 'Uttara, Dhaka',
            'tax_payer_id' => '5222222215310',
            'language' => 'en',
            'department_id' => null,
            'designation_id' => null,
            'salary' => '200000000.00',
            'salary_type' => 'Yearly',
            'created_at' => '2021-04-07T07:04:03.000000Z',
            'updated_at' => '2022-12-31T10:36:37.000000Z',
        ];
        return $admin;

        // return [
        //     'prefix' => 'Md.',
        //     'name' => 'Super',
        //     'last_name' => 'Admin',
        //     'emp_id' => null,
        //     'username' => 'superadmin',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('password'),
        //     'email_verified_at' => null,
        //     'shift_id' => null,
        //     'role_type' => 1,
        //     'allow_login' => 1,
        //     'branch_id' => null,
        //     'status' => 1,
        //     'sales_commission_percent' => null,
        //     'max_sales_discount_percent' => null,
        //     'phone' => null,
        //     'date_of_birth' => null,
        //     'gender' => null,
        //     'marital_status' => null,
        //     'blood_group' => null,
        //     'photo' => null,
        //     'facebook_link' => null,
        //     'twitter_link' => null,
        //     'instagram_link' => null,
        //     'social_media_1' => null,
        //     'social_media_2' => null,
        //     'custom_field_1' => null,
        //     'custom_field_2' => null,
        //     'guardian_name' => null,
        //     'id_proof_name' => null,
        //     'id_proof_number' => null,
        //     'permanent_address' => null,
        //     'current_address' => null,
        //     'bank_ac_holder_name' => null,
        //     'bank_ac_no' => null,
        //     'bank_name' => null,
        //     'bank_identifier_code' => null,
        //     'bank_branch' => null,
        //     'tax_payer_id' => null,
        //     'language' => 'en',
        //     'department_id' => null,
        //     'designation_id' => null,
        //     'salary' => null,
        //     'salary_type' => null,
        //     'created_at' => now(),
        //     'updated_at' => null,
        // ];
    }
}
