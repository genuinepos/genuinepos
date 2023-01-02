<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $users = array(
            array('id' => '1','prefix' => 'Md','name' => 'Super','last_name' => 'Admin','emp_id' => NULL,'username' => 'superadmin','email' => 'gollachuttelecare@gmail.com','shift_id' => null,'role_type' => '1','allow_login' => '1','branch_id' => NULL,'status' => '0','password' => '$2y$10$rd3uLXbr7OXtcZAh5VAj1u.nHtBpy0.gZx5HYXJ1uSR/TpT/nVBai','remember_token' => NULL,'sales_commission_percent' => '10.00','max_sales_discount_percent' => '5.00','phone' => '0124152410','date_of_birth' => '1998-10-5','gender' => 'Male','marital_status' => 'Married','blood_group' => 'a+','photo' => 'default.png','facebook_link' => 'hellol','twitter_link' => 'dib na','instagram_link' => 'valko oh','social_media_1' => NULL,'social_media_2' => NULL,'custom_field_1' => NULL,'custom_field_2' => NULL,'guardian_name' => 'fasdfasd','id_proof_name' => 'nbau','id_proof_number' => 'nay bollan ma','permanent_address' => NULL,'current_address' => NULL,'bank_ac_holder_name' => 'Businesss','bank_ac_no' => '012441200152','bank_name' => 'sonali','bank_identifier_code' => '0000000000000000','bank_branch' => 'uttara','tax_payer_id' => '5222222215310','language' => 'en','department_id' => '4','designation_id' => '1','salary' => '200000000.00','salary_type' => 'Yearly','created_at' => '2021-04-07 13:04:03','updated_at' => '2022-12-31 16:36:37'),
            array('id' => '2','prefix' => NULL,'name' => 'admin','last_name' => NULL,'emp_id' => NULL,'username' => 'admin','email' => 'admin@example.com','shift_id' => null,'role_type' => '1','allow_login' => '1','branch_id' => '1','status' => '0','password' => '$2y$10$Cm0U.qGx.32sMqKhDyLJrehbYFDFzTzz/.QYpxjv2MeCPjB7vYIs.','remember_token' => NULL,'sales_commission_percent' => '0.00','max_sales_discount_percent' => '0.00','phone' => '0121015420','date_of_birth' => NULL,'gender' => NULL,'marital_status' => NULL,'blood_group' => NULL,'photo' => 'default.png','facebook_link' => NULL,'twitter_link' => NULL,'instagram_link' => NULL,'social_media_1' => NULL,'social_media_2' => NULL,'custom_field_1' => NULL,'custom_field_2' => NULL,'guardian_name' => NULL,'id_proof_name' => NULL,'id_proof_number' => NULL,'permanent_address' => NULL,'current_address' => NULL,'bank_ac_holder_name' => NULL,'bank_ac_no' => NULL,'bank_name' => NULL,'bank_identifier_code' => NULL,'bank_branch' => NULL,'tax_payer_id' => NULL,'language' => NULL,'department_id' => null,'designation_id' => '1','salary' => '510121.00','salary_type' => 'Monthly','created_at' => '2022-11-23 13:00:57','updated_at' => '2022-12-30 12:39:15'),
            array('id' => '3','prefix' => NULL,'name' => 'Test User','last_name' => NULL,'emp_id' => NULL,'username' => 'user','email' => 'dev@gmail.com','shift_id' => '6','role_type' => '2','allow_login' => '1','branch_id' => '1','status' => '0','password' => '$2y$10$85GmTNUVIAxVaLahoXFZ1.CDzQhjZDTwOy.gGGev92xC8EBsM20Zi','remember_token' => NULL,'sales_commission_percent' => '0.00','max_sales_discount_percent' => '0.00','phone' => '0124512015','date_of_birth' => NULL,'gender' => NULL,'marital_status' => NULL,'blood_group' => NULL,'photo' => 'default.png','facebook_link' => NULL,'twitter_link' => NULL,'instagram_link' => NULL,'social_media_1' => NULL,'social_media_2' => NULL,'custom_field_1' => NULL,'custom_field_2' => NULL,'guardian_name' => NULL,'id_proof_name' => NULL,'id_proof_number' => NULL,'permanent_address' => NULL,'current_address' => NULL,'bank_ac_holder_name' => NULL,'bank_ac_no' => NULL,'bank_name' => NULL,'bank_identifier_code' => NULL,'bank_branch' => NULL,'tax_payer_id' => NULL,'language' => NULL,'department_id' => null,'designation_id' => '1','salary' => '20000.00','salary_type' => 'Monthly','created_at' => '2022-12-30 12:17:17','updated_at' => '2022-12-30 12:38:02')
          );
          \DB::table('users')->insert($users);
    }
}
