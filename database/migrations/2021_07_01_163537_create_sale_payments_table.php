<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sale_payments', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('invoice_id')->nullable();
			$table->bigInteger('sale_id')->unsigned()->nullable()->index('sale_payments_sale_id_foreign');
			$table->bigInteger('customer_id')->unsigned()->nullable()->index('sale_payments_customer_id_foreign');
			$table->bigInteger('account_id')->unsigned()->nullable()->index('sale_payments_account_id_foreign');
			$table->string('pay_mode')->nullable();
			$table->decimal('paid_amount', 22)->default(0.00);
			$table->boolean('payment_on')->default(1)->comment('1=sale_invoice_due;2=customer_due');
			$table->boolean('payment_type')->default(1)->comment('1=sale_due;2=return_due');
			$table->boolean('payment_status')->nullable()->comment('1=due;2=partial;3=paid');
			$table->string('date');
			$table->string('time');
			$table->string('month');
			$table->string('year');
			$table->timestamp('report_date')->default(DB::raw('CURRENT_TIMESTAMP'));
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
			$table->text('note')->nullable();
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
		Schema::drop('sale_payments');
	}

}
