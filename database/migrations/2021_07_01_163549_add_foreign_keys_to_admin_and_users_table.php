<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAdminAndUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('admin_and_users', function(Blueprint $table)
		{
			$table->foreign('branch_id')->references('id')->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('department_id')->references('id')->on('hrm_department')->onUpdate('NO ACTION')->onDelete('SET NULL');
			$table->foreign('designation_id')->references('id')->on('hrm_designations')->onUpdate('NO ACTION')->onDelete('SET NULL');
			$table->foreign('role_id')->references('id')->on('roles')->onUpdate('NO ACTION')->onDelete('SET NULL');
			$table->foreign('role_permission_id')->references('id')->on('role_permissions')->onUpdate('NO ACTION')->onDelete('SET NULL');
			$table->foreign('shift_id')->references('id')->on('hrm_shifts')->onUpdate('NO ACTION')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('admin_and_users', function(Blueprint $table)
		{
			$table->dropForeign('admin_and_users_branch_id_foreign');
			$table->dropForeign('admin_and_users_department_id_foreign');
			$table->dropForeign('admin_and_users_designation_id_foreign');
			$table->dropForeign('admin_and_users_role_id_foreign');
			$table->dropForeign('admin_and_users_role_permission_id_foreign');
			$table->dropForeign('admin_and_users_shift_id_foreign');
		});
	}

}
