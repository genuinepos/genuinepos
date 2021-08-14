<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExpansePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('expanse_payments', function(Blueprint $table)
		{
			$table->foreign('account_id')->references('id')->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('expanse_id')->references('id')->on('expanses')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('expanse_payments', function(Blueprint $table)
		{
			$table->dropForeign('expanse_payments_account_id_foreign');
			$table->dropForeign('expanse_payments_expanse_id_foreign');
		});
	}

}
