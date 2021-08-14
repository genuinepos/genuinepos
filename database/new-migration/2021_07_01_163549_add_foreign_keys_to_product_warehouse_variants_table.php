<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductWarehouseVariantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_warehouse_variants', function(Blueprint $table)
		{
			$table->foreign('product_id')->references('id')->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('product_variant_id')->references('id')->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('product_warehouse_id')->references('id')->on('product_warehouses')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_warehouse_variants', function(Blueprint $table)
		{
			$table->dropForeign('product_warehouse_variants_product_id_foreign');
			$table->dropForeign('product_warehouse_variants_product_variant_id_foreign');
			$table->dropForeign('product_warehouse_variants_product_warehouse_id_foreign');
		});
	}

}
