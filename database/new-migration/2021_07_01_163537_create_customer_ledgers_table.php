<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerLedgersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_ledgers', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('customer_id')->unsigned()->nullable()->index('customer_ledgers_customer_id_foreign');
			$table->bigInteger('sale_id')->unsigned()->nullable()->index('customer_ledgers_sale_id_foreign');
			$table->bigInteger('sale_payment_id')->unsigned()->nullable()->index('customer_ledgers_sale_payment_id_foreign');
			$table->bigInteger('money_receipt_id')->unsigned()->nullable()->index('customer_ledgers_money_receipt_id_foreign');
			$table->boolean('row_type')->default(1)->comment('1=sale;2=sale_payment;3=opening_balance;3=money_receipt');
			$table->decimal('amount', 22)->nullable()->comment('only_for_opening_balance');
			$table->boolean('is_advanced')->default(0)->comment('only_for_money_receipt');
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
		Schema::drop('customer_ledgers');
	}

}
