<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_return_products', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('purchase_return_id')->unsigned()->index('purchase_return_products_purchase_return_id_foreign');
			$table->bigInteger('purchase_product_id')->unsigned()->nullable()->index('purchase_return_products_purchase_product_id_foreign')->comment('this_field_only_for_purchase_invoice_return.');
			$table->bigInteger('product_id')->unsigned()->nullable()->index('purchase_return_products_product_id_foreign');
			$table->bigInteger('product_variant_id')->unsigned()->nullable()->index('purchase_return_products_product_variant_id_foreign');
			$table->decimal('return_qty', 22)->default(0.00);
			$table->string('unit')->nullable();
			$table->decimal('return_subtotal', 22)->default(0.00);
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
		Schema::drop('purchase_return_products');
	}

}
