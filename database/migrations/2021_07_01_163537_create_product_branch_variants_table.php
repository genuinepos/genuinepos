<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBranchVariantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_branch_variants', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('product_branch_id')->unsigned()->nullable()->index('product_branch_variants_product_branch_id_foreign');
			$table->bigInteger('product_id')->unsigned()->nullable()->index('product_branch_variants_product_id_foreign');
			$table->bigInteger('product_variant_id')->unsigned()->nullable()->index('product_branch_variants_product_variant_id_foreign');
			$table->decimal('variant_quantity', 22)->nullable()->default(0.00);
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
		Schema::drop('product_branch_variants');
	}

}
