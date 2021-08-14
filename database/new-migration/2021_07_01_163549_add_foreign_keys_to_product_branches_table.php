<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductBranchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_branches', function(Blueprint $table)
		{
			$table->foreign('branch_id')->references('id')->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('product_id')->references('id')->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_branches', function(Blueprint $table)
		{
			$table->dropForeign('product_branches_branch_id_foreign');
			$table->dropForeign('product_branches_product_id_foreign');
		});
	}

}
