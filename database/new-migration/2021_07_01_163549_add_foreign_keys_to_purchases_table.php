<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPurchasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchases', function(Blueprint $table)
		{
			$table->foreign('branch_id')->references('id')->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('warehouse_id')->references('id')->on('warehouses')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchases', function(Blueprint $table)
		{
			$table->dropForeign('purchases_branch_id_foreign');
			$table->dropForeign('purchases_supplier_id_foreign');
			$table->dropForeign('purchases_warehouse_id_foreign');
		});
	}

}
