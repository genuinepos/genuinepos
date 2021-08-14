<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sale_products', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('sale_id')->unsigned()->index('sale_products_sale_id_foreign');
			$table->bigInteger('product_id')->unsigned()->index('sale_products_product_id_foreign');
			$table->bigInteger('product_variant_id')->unsigned()->nullable()->index('sale_products_product_variant_id_foreign');
			$table->decimal('quantity', 22)->default(0.00);
			$table->string('unit')->nullable();
			$table->boolean('unit_discount_type')->default(1);
			$table->decimal('unit_discount', 22)->default(0.00);
			$table->decimal('unit_discount_amount', 22)->default(0.00);
			$table->decimal('unit_tax_percent', 22)->default(0.00);
			$table->decimal('unit_tax_amount', 22)->default(0.00);
			$table->decimal('unit_cost_inc_tax', 22)->default(0.00)->comment('this_col_for_invoice_profit_report');
			$table->decimal('unit_price_exc_tax', 22)->default(0.00);
			$table->decimal('unit_price_inc_tax', 22)->default(0.00);
			$table->decimal('subtotal', 22)->default(0.00);
			$table->text('description')->nullable();
			$table->decimal('ex_quantity', 22)->default(0.00);
			$table->boolean('ex_status')->default(0)->comment('0=no_exchanged,1=prepare_to_exchange,2=exchanged');
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
		Schema::drop('sale_products');
	}

}
