<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductWarehousesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_warehouses', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('warehouse_id')->unsigned()->nullable()->index('product_warehouses_warehouse_id_foreign');
			$table->bigInteger('product_id')->unsigned()->nullable()->index('product_warehouses_product_id_foreign');
			$table->decimal('product_quantity', 22)->nullable()->default(0.00);
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
		Schema::drop('product_warehouses');
	}

}
