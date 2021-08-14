<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSvDeviceModelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sv_device_models', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('brand_id')->unsigned()->nullable()->index('sv_device_models_brand_id_foreign');
			$table->bigInteger('device_id')->unsigned()->nullable()->index('sv_device_models_device_id_foreign');
			$table->string('model_name')->nullable();
			$table->text('checklist')->nullable();
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
		Schema::drop('sv_device_models');
	}

}
