<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_variants', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('product_id')->unsigned()->index('product_variants_product_id_foreign');
			$table->string('variant_name');
			$table->string('variant_code');
			$table->decimal('variant_quantity', 22)->default(0.00);
			$table->decimal('number_of_sale', 22)->default(0.00);
			$table->decimal('total_transfered', 22)->default(0.00);
			$table->decimal('total_adjusted', 22)->default(0.00);
			$table->decimal('variant_cost', 22);
			$table->decimal('variant_cost_with_tax', 22)->default(0.00);
			$table->decimal('variant_profit', 22)->default(0.00);
			$table->decimal('variant_price', 22);
			$table->string('variant_image', 191)->nullable();
			$table->boolean('is_purchased')->default(0);
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
		Schema::drop('product_variants');
	}

}
