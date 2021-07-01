<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToHrmLeavesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hrm_leaves', function(Blueprint $table)
		{
			$table->foreign('employee_id')->references('id')->on('admin_and_users')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('leave_id')->references('id')->on('hrm_leavetypes')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hrm_leaves', function(Blueprint $table)
		{
			$table->dropForeign('hrm_leaves_employee_id_foreign');
			$table->dropForeign('hrm_leaves_leave_id_foreign');
		});
	}

}
