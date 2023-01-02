<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id')->index('cash_flows_account_id_foreign');
            $table->unsignedBigInteger('sender_account_id')->nullable()->index('cash_flows_sender_account_id_foreign');
            $table->unsignedBigInteger('receiver_account_id')->nullable()->index('cash_flows_receiver_account_id_foreign');
            $table->unsignedBigInteger('purchase_payment_id')->nullable()->index('cash_flows_purchase_payment_id_foreign');
            $table->unsignedBigInteger('supplier_payment_id')->nullable()->index('cash_flows_supplier_payment_id_foreign');
            $table->unsignedBigInteger('sale_payment_id')->nullable()->index('cash_flows_sale_payment_id_foreign');
            $table->unsignedBigInteger('customer_payment_id')->nullable()->index('cash_flows_customer_payment_id_foreign');
            $table->unsignedBigInteger('expanse_payment_id')->nullable()->index('cash_flows_expanse_payment_id_foreign');
            $table->unsignedBigInteger('money_receipt_id')->nullable()->index('cash_flows_money_receipt_id_foreign');
            $table->unsignedBigInteger('payroll_id')->nullable()->index('cash_flows_payroll_id_foreign');
            $table->unsignedBigInteger('payroll_payment_id')->nullable()->index('cash_flows_payroll_payment_id_foreign');
            $table->unsignedBigInteger('loan_id')->nullable()->index('cash_flows_loan_id_foreign');
            $table->decimal('debit', 22)->nullable();
            $table->decimal('credit', 22)->nullable();
            $table->decimal('balance', 22)->default(0);
            $table->tinyInteger('transaction_type')->comment('1=payment;2=sale_payment;3=purchase_payment;4=fundTransfer;5=deposit;6=expansePayment;7=openingBalance;8=payroll_payment;9=money_receipt;10=loan-get/pay;11=loan_ins_payment/receive;12=supplier_payment;13=customer_payment');
            $table->tinyInteger('cash_type')->nullable()->comment('1=debit;2=credit;');
            $table->string('date');
            $table->string('month');
            $table->string('year');
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('related_cash_flow_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('loan_payment_id')->nullable()->index('cash_flows_loan_payment_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_flows');
    }
}
