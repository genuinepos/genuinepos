<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductBranchVariantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_branch_variants', function(Blueprint $table)
		{
			$table->foreign('product_branch_id')->references('id')->on('product_branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('product_id')->references('id')->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('product_variant_id')->references('id')->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_branch_variants', function(Blueprint $table)
		{
			$table->dropForeign('product_branch_variants_product_branch_id_foreign');
			$table->dropForeign('product_branch_variants_product_id_foreign');
			$table->dropForeign('product_branch_variants_product_variant_id_foreign');
		});
	}

}
