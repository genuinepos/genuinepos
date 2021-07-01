<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToHrmAttendancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hrm_attendances', function(Blueprint $table)
		{
			$table->foreign('user_id')->references('id')->on('admin_and_users')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hrm_attendances', function(Blueprint $table)
		{
			$table->dropForeign('hrm_attendances_user_id_foreign');
		});
	}

}
