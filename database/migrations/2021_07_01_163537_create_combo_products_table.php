<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComboProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('combo_products', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('product_id')->unsigned()->nullable()->index('combo_products_product_id_foreign');
			$table->bigInteger('combo_product_id')->unsigned()->nullable()->index('combo_products_combo_product_id_foreign');
			$table->decimal('quantity', 22)->nullable()->default(0.00);
			$table->bigInteger('product_variant_id')->unsigned()->nullable()->index('combo_products_product_variant_id_foreign');
			$table->boolean('delete_in_update')->default(0);
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
		Schema::drop('combo_products');
	}

}
