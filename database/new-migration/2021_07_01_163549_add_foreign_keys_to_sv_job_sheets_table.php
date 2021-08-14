<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSvJobSheetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sv_job_sheets', function(Blueprint $table)
		{
			$table->foreign('branch_id')->references('id')->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('brand_id')->references('id')->on('brands')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('customer_id')->references('id')->on('customers')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('device_id')->references('id')->on('sv_devices')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('model_id')->references('id')->on('sv_device_models')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('status_id')->references('id')->on('sv_status')->onUpdate('NO ACTION')->onDelete('SET NULL');
			$table->foreign('user_id')->references('id')->on('admin_and_users')->onUpdate('NO ACTION')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sv_job_sheets', function(Blueprint $table)
		{
			$table->dropForeign('sv_job_sheets_branch_id_foreign');
			$table->dropForeign('sv_job_sheets_brand_id_foreign');
			$table->dropForeign('sv_job_sheets_customer_id_foreign');
			$table->dropForeign('sv_job_sheets_device_id_foreign');
			$table->dropForeign('sv_job_sheets_model_id_foreign');
			$table->dropForeign('sv_job_sheets_status_id_foreign');
			$table->dropForeign('sv_job_sheets_user_id_foreign');
		});
	}

}
