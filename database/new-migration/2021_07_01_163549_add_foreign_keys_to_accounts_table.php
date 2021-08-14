<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('accounts', function(Blueprint $table)
		{
			$table->foreign('account_type_id')->references('id')->on('account_types')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('bank_id')->references('id')->on('banks')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('accounts', function(Blueprint $table)
		{
			$table->dropForeign('accounts_account_type_id_foreign');
			$table->dropForeign('accounts_bank_id_foreign');
		});
	}

}
