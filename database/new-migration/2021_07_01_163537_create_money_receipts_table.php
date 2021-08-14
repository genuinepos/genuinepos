<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyReceiptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('money_receipts', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('invoice_id', 191)->nullable();
			$table->decimal('amount', 22)->default(0.00);
			$table->decimal('received_amount', 22)->default(0.00);
			$table->bigInteger('customer_id')->unsigned()->index('money_receipts_customer_id_foreign');
			$table->bigInteger('branch_id')->unsigned()->nullable()->index('money_receipts_branch_id_foreign');
			$table->text('note')->nullable();
			$table->string('payment_method', 191)->nullable();
			$table->string('status', 191)->nullable();
			$table->boolean('is_amount')->default(0);
			$table->boolean('is_date')->default(0);
			$table->boolean('is_note')->default(0);
			$table->boolean('is_invoice_id')->default(0);
			$table->string('date')->nullable();
			$table->string('month')->nullable();
			$table->string('year')->nullable();
			$table->dateTime('date_ts')->nullable();
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
		Schema::drop('money_receipts');
	}

}
