<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferStockToBranchProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transfer_stock_to_branch_products', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('transfer_stock_id')->unsigned()->index('transfer_stock_to_branch_products_transfer_stock_id_foreign');
			$table->bigInteger('product_id')->unsigned()->index('transfer_stock_to_branch_products_product_id_foreign');
			$table->bigInteger('product_variant_id')->unsigned()->nullable()->index('transfer_stock_to_branch_products_product_variant_id_foreign');
			$table->decimal('unit_price', 22);
			$table->decimal('quantity', 22);
			$table->decimal('received_qty', 22)->default(0.00);
			$table->string('unit')->nullable();
			$table->decimal('subtotal', 22);
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
		Schema::drop('transfer_stock_to_branch_products');
	}

}
