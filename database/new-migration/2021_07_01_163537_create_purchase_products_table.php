<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_products', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('purchase_id')->unsigned()->index('purchase_products_purchase_id_foreign');
			$table->bigInteger('product_id')->unsigned()->index('purchase_products_product_id_foreign');
			$table->bigInteger('product_variant_id')->unsigned()->nullable()->index('purchase_products_product_variant_id_foreign');
			$table->decimal('quantity', 22)->nullable()->default(0.00);
			$table->string('unit')->nullable();
			$table->decimal('unit_cost', 22)->default(0.00);
			$table->decimal('unit_discount', 22)->default(0.00);
			$table->decimal('unit_cost_with_discount', 22)->default(0.00);
			$table->decimal('subtotal', 22)->default(0.00)->comment('Without_tax');
			$table->decimal('unit_tax_percent', 22)->default(0.00);
			$table->decimal('unit_tax', 22)->default(0.00);
			$table->decimal('net_unit_cost', 22)->default(0.00)->comment('With_tax');
			$table->decimal('line_total', 22)->default(0.00);
			$table->decimal('profit_margin', 22)->default(0.00);
			$table->decimal('selling_price', 22)->default(0.00);
			$table->boolean('is_received')->default(0);
			$table->string('lot_no', 191)->nullable();
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
		Schema::drop('purchase_products');
	}

}
