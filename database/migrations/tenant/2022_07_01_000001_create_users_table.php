<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('prefix', 20)->nullable();
            $table->string('name', 50);
            $table->string('last_name', 50)->nullable();
            $table->string('emp_id', 50)->nullable();
            $table->string('username', 40)->nullable();
            $table->string('email')->nullable()->unique('users_email_unique');
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable()->index('users_shift_id_foreign');
            $table->integer('role_type')->nullable()->comment('1=super_admin,2=admin,3=others');
            $table->boolean('allow_login')->default(false);
            $table->unsignedBigInteger('branch_id')->nullable()->index('users_branch_id_foreign');
            $table->boolean('is_belonging_an_area')->default(true);
            $table->boolean('status')->default(true);
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->decimal('sales_commission_percent')->default(0);
            $table->decimal('max_sales_discount_percent')->default(0);
            $table->string('phone', 50)->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('marital_status', 10)->nullable();
            $table->string('blood_group', 8)->nullable();
            $table->string('photo', 191)->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('id_proof_name')->nullable();
            $table->string('id_proof_number')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('current_address')->nullable();
            $table->string('bank_ac_holder_name')->nullable();
            $table->string('bank_ac_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_identifier_code')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('tax_payer_id')->nullable();
            $table->string('language', 6)->nullable();
            $table->unsignedBigInteger('department_id')->nullable()->index('users_department_id_foreign');
            $table->unsignedBigInteger('designation_id')->nullable()->index('users_designation_id_foreign');
            $table->decimal('salary', 22)->default(0);
            $table->string('salary_type', 80)->nullable();
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['department_id'])->references(['id'])->on('hrm_departments')->onDelete('SET NULL');
            $table->foreign(['designation_id'])->references(['id'])->on('hrm_designations')->onDelete('SET NULL');
            $table->foreign(['shift_id'])->references(['id'])->on('hrm_shifts')->onDelete('SET NULL');
            $table->foreign(['currency_id'])->references('id')->on('currencies')->onDelete('set null');
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
};
