<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpansePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expanse_payments', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('invoice_id')->nullable();
			$table->bigInteger('expanse_id')->unsigned()->index('expanse_payments_expanse_id_foreign');
			$table->bigInteger('account_id')->unsigned()->nullable()->index('expanse_payments_account_id_foreign');
			$table->string('pay_mode')->nullable();
			$table->decimal('paid_amount', 22)->default(0.00);
			$table->boolean('payment_status')->nullable()->comment('1=due;2=partial;3=paid');
			$table->string('date');
			$table->string('month');
			$table->string('year');
			$table->string('card_no')->nullable();
			$table->string('card_holder')->nullable();
			$table->string('card_type')->nullable();
			$table->string('card_transaction_no')->nullable();
			$table->string('card_month')->nullable();
			$table->string('card_year')->nullable();
			$table->string('card_secure_code')->nullable();
			$table->string('account_no')->nullable();
			$table->string('cheque_no')->nullable();
			$table->string('transaction_no')->nullable();
			$table->string('attachment')->nullable();
			$table->bigInteger('admin_id')->unsigned()->nullable();
			$table->text('note')->nullable();
			$table->timestamp('report_date')->default(DB::raw('CURRENT_TIMESTAMP'));
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
		Schema::drop('expanse_payments');
	}

}
