<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sale_return_products', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('sale_return_id')->unsigned()->index('sale_return_products_sale_return_id_foreign');
			$table->bigInteger('sale_product_id')->unsigned()->index('sale_return_products_sale_product_id_foreign');
			$table->decimal('return_qty', 22)->default(0.00);
			$table->string('unit')->nullable();
			$table->decimal('return_subtotal', 22)->default(0.00);
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
		Schema::drop('sale_return_products');
	}

}
