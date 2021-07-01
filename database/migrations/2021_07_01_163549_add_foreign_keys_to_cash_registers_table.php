<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCashRegistersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cash_registers', function(Blueprint $table)
		{
			$table->foreign('account_id')->references('id')->on('accounts')->onUpdate('NO ACTION')->onDelete('SET NULL');
			$table->foreign('admin_id')->references('id')->on('admin_and_users')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
		Schema::table('cash_registers', function(Blueprint $table)
		{
			$table->dropForeign('cash_registers_account_id_foreign');
			$table->dropForeign('cash_registers_admin_id_foreign');
			$table->dropForeign('cash_registers_branch_id_foreign');
			$table->dropForeign('cash_registers_warehouse_id_foreign');
		});
	}

}
