<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customers', function(Blueprint $table)
		{
			$table->foreign('customer_group_id')->references('id')->on('customer_groups')->onUpdate('NO ACTION')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customers', function(Blueprint $table)
		{
			$table->dropForeign('customers_customer_group_id_foreign');
		});
	}

}
