<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('supplier_products', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('supplier_id')->unsigned()->index('supplier_products_supplier_id_foreign');
			$table->bigInteger('product_id')->unsigned()->index('supplier_products_product_id_foreign');
			$table->bigInteger('product_variant_id')->unsigned()->nullable()->index('supplier_products_product_variant_id_foreign');
			$table->bigInteger('label_qty')->default(0);
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
		Schema::drop('supplier_products');
	}

}
