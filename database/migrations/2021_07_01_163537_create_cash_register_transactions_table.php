<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegisterTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cash_register_transactions', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('cash_register_id')->unsigned()->nullable()->index('cash_register_transactions_cash_register_id_foreign');
			$table->bigInteger('sale_id')->unsigned()->nullable()->index('cash_register_transactions_sale_id_foreign');
			$table->boolean('cash_type')->default(2)->comment('1=debit;2=credit');
			$table->boolean('transaction_type')->default(2)->comment('1=initial;2=sale');
			$table->decimal('amount', 22)->nullable();
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
		Schema::drop('cash_register_transactions');
	}

}
