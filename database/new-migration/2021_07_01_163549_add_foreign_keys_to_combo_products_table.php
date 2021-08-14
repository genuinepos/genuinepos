<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToComboProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('combo_products', function(Blueprint $table)
		{
			$table->foreign('combo_product_id')->references('id')->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('product_id')->references('id')->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('product_variant_id')->references('id')->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('combo_products', function(Blueprint $table)
		{
			$table->dropForeign('combo_products_combo_product_id_foreign');
			$table->dropForeign('combo_products_product_id_foreign');
			$table->dropForeign('combo_products_product_variant_id_foreign');
		});
	}

}
