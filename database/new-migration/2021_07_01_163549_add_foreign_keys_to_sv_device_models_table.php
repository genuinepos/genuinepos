<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSvDeviceModelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sv_device_models', function(Blueprint $table)
		{
			$table->foreign('brand_id')->references('id')->on('brands')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('device_id')->references('id')->on('sv_devices')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sv_device_models', function(Blueprint $table)
		{
			$table->dropForeign('sv_device_models_brand_id_foreign');
			$table->dropForeign('sv_device_models_device_id_foreign');
		});
	}

}
