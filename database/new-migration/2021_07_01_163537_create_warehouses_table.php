<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('warehouses', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('warehouse_name');
			$table->string('warehouse_code');
			$table->string('phone')->nullable();
			$table->text('address')->nullable();
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
		Schema::drop('warehouses');
	}

}
