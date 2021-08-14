<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFlowsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cash_flows', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('account_id')->unsigned()->index('cash_flows_account_id_foreign');
			$table->bigInteger('sender_account_id')->unsigned()->nullable()->index('cash_flows_sender_account_id_foreign');
			$table->bigInteger('receiver_account_id')->unsigned()->nullable()->index('cash_flows_receiver_account_id_foreign');
			$table->bigInteger('purchase_payment_id')->unsigned()->nullable()->index('cash_flows_purchase_payment_id_foreign');
			$table->bigInteger('sale_payment_id')->unsigned()->nullable()->index('cash_flows_sale_payment_id_foreign');
			$table->bigInteger('expanse_payment_id')->unsigned()->nullable()->index('cash_flows_expanse_payment_id_foreign');
			$table->bigInteger('money_receipt_id')->unsigned()->nullable()->index('cash_flows_money_receipt_id_foreign');
			$table->bigInteger('payroll_id')->unsigned()->nullable()->index('cash_flows_payroll_id_foreign');
			$table->bigInteger('payroll_payment_id')->unsigned()->nullable()->index('cash_flows_payroll_payment_id_foreign');
			$table->decimal('debit', 22)->nullable();
			$table->decimal('credit', 22)->nullable();
			$table->decimal('balance', 22)->default(0.00);
			$table->boolean('transaction_type')->comment('1=payment;2=sale_payment;3=purchase_payment;4=fundTransfer;5=deposit;6=expansePayment;7=openingBalance;8=payroll_payment;9=money_receipt');
			$table->boolean('cash_type')->nullable()->comment('1=debit;2=credit;');
			$table->string('date');
			$table->string('month');
			$table->string('year');
			$table->timestamp('report_date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->bigInteger('admin_id')->unsigned()->nullable();
			$table->bigInteger('related_cash_flow_id')->unsigned()->nullable();
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
		Schema::drop('cash_flows');
	}

}
