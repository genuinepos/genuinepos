<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStockAdjustmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stock_adjustments', function(Blueprint $table)
		{
			$table->foreign('admin_id')->references('id')->on('admin_and_users')->onUpdate('NO ACTION')->onDelete('SET NULL');
			$table->foreign('branch_id')->references('id')->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
		Schema::table('stock_adjustments', function(Blueprint $table)
		{
			$table->dropForeign('stock_adjustments_admin_id_foreign');
			$table->dropForeign('stock_adjustments_branch_id_foreign');
			$table->dropForeign('stock_adjustments_warehouse_id_foreign');
		});
	}

}
