<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_adjustment_products', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('stock_adjustment_id')->unsigned()->index('stock_adjustment_products_stock_adjustment_id_foreign');
			$table->bigInteger('product_id')->unsigned()->index('stock_adjustment_products_product_id_foreign');
			$table->bigInteger('product_variant_id')->unsigned()->nullable()->index('stock_adjustment_products_product_variant_id_foreign');
			$table->decimal('quantity', 22)->default(0.00);
			$table->string('unit')->nullable();
			$table->decimal('unit_cost_inc_tax', 22)->default(0.00);
			$table->decimal('subtotal', 22)->default(0.00);
			$table->boolean('is_delete_in_update')->default(0);
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
		Schema::drop('stock_adjustment_products');
	}

}
