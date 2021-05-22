<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminAndUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_and_users', function (Blueprint $table) {
            $table->id();
            $table->string('prefix')->nullable();
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->unique();
            $table->integer('role_type')->nullable()->comment('1=super_admin,2=admin,3=others');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('role_permission_id')->nullable();
            $table->boolean('allow_login')->default(0);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->boolean('status')->default(1);
            $table->string('password')->nullable();
            $table->decimal('sales_commission_percent')->default(0.00);
            $table->decimal('max_sales_discount_percent')->default(0.00);
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
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            $table->foreign('role_permission_id')->references('id')->on('role_permissions')->onDelete('set null');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_and_users');
    }
}
