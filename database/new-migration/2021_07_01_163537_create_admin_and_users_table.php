<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminAndUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_and_users', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('prefix')->nullable();
			$table->string('name');
			$table->string('last_name')->nullable();
			$table->string('emp_id', 50)->nullable();
			$table->string('username')->nullable();
			$table->string('email')->unique();
			$table->bigInteger('shift_id')->unsigned()->nullable()->index('admin_and_users_shift_id_foreign');
			$table->integer('role_type')->nullable()->comment('1=super_admin,2=admin,3=others');
			$table->bigInteger('role_id')->unsigned()->nullable()->index('admin_and_users_role_id_foreign');
			$table->bigInteger('role_permission_id')->unsigned()->nullable()->index('admin_and_users_role_permission_id_foreign');
			$table->boolean('allow_login')->default(0);
			$table->bigInteger('branch_id')->unsigned()->nullable()->index('admin_and_users_branch_id_foreign');
			$table->boolean('status')->default(1);
			$table->string('password')->nullable();
			$table->tinyInteger('pin')->nullable();
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
			$table->bigInteger('department_id')->unsigned()->nullable()->index('admin_and_users_department_id_foreign');
			$table->bigInteger('designation_id')->unsigned()->nullable()->index('admin_and_users_designation_id_foreign');
			$table->decimal('salary', 22)->default(0.00);
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
		Schema::drop('admin_and_users');
	}

}
