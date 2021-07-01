<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToHrmAllowanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hrm_allowance', function(Blueprint $table)
		{
			$table->foreign('employee_id')->references('id')->on('admin_and_users')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hrm_allowance', function(Blueprint $table)
		{
			$table->dropForeign('hrm_allowance_employee_id_foreign');
		});
	}

}
