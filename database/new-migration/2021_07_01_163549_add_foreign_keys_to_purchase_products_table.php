<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPurchaseProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_products', function(Blueprint $table)
		{
			$table->foreign('product_id')->references('id')->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('product_variant_id')->references('id')->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('purchase_id')->references('id')->on('purchases')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase_products', function(Blueprint $table)
		{
			$table->dropForeign('purchase_products_product_id_foreign');
			$table->dropForeign('purchase_products_product_variant_id_foreign');
			$table->dropForeign('purchase_products_purchase_id_foreign');
		});
	}

}
