<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accounts', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('name');
			$table->string('account_number');
			$table->bigInteger('bank_id')->unsigned()->nullable()->index('accounts_bank_id_foreign');
			$table->bigInteger('account_type_id')->unsigned()->nullable()->index('accounts_account_type_id_foreign');
			$table->decimal('opening_balance', 22)->default(0.00);
			$table->decimal('debit', 22)->default(0.00);
			$table->decimal('credit', 22)->default(0.00);
			$table->decimal('balance', 22)->default(0.00);
			$table->text('remark')->nullable();
			$table->boolean('status')->default(1);
			$table->bigInteger('admin_id')->unsigned()->nullable();
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
		Schema::drop('accounts');
	}

}
