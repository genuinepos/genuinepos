<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('prefix')->nullable();
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('emp_id', 50)->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable()->unique('users_email_unique');
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->unsignedBigInteger('shift_id')->nullable()->index('users_shift_id_foreign');
            $table->integer('role_type')->nullable()->comment('1=super_admin,2=admin,3=others');
            $table->boolean('allow_login')->default(false);
            $table->unsignedBigInteger('branch_id')->nullable()->index('users_branch_id_foreign');
            $table->boolean('status')->default(true);
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->decimal('sales_commission_percent')->default(0);
            $table->decimal('max_sales_discount_percent')->default(0);
            $table->string('phone')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('photo')->default('default.png');
            $table->string('facebook_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('social_media_1')->nullable();
            $table->string('social_media_2')->nullable();
            $table->string('custom_field_1')->nullable();
            $table->string('custom_field_2')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('id_proof_name')->nullable();
            $table->string('id_proof_number')->nullable();
            $table->text('permanent_address')->nullable();
            $table->text('current_address')->nullable();
            $table->string('bank_ac_holder_name')->nullable();
            $table->string('bank_ac_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_identifier_code')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('tax_payer_id')->nullable();
            $table->string('language')->nullable();
            $table->unsignedBigInteger('department_id')->nullable()->index('users_department_id_foreign');
            $table->unsignedBigInteger('designation_id')->nullable()->index('users_designation_id_foreign');
            $table->decimal('salary', 22)->default(0);
            $table->string('salary_type', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
