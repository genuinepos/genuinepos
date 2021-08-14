<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPurchaseReturnProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_return_products', function(Blueprint $table)
		{
			$table->foreign('product_id')->references('id')->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('product_variant_id')->references('id')->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('purchase_product_id')->references('id')->on('purchase_products')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('purchase_return_id')->references('id')->on('purchase_returns')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase_return_products', function(Blueprint $table)
		{
			$table->dropForeign('purchase_return_products_product_id_foreign');
			$table->dropForeign('purchase_return_products_product_variant_id_foreign');
			$table->dropForeign('purchase_return_products_purchase_product_id_foreign');
			$table->dropForeign('purchase_return_products_purchase_return_id_foreign');
		});
	}

}
