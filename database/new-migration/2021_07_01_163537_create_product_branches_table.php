<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBranchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_branches', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('branch_id')->unsigned()->nullable()->index('product_branches_branch_id_foreign');
			$table->bigInteger('product_id')->unsigned()->nullable()->index('product_branches_product_id_foreign');
			$table->decimal('product_quantity', 22)->nullable()->default(0.00);
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
		Schema::drop('product_branches');
	}

}
