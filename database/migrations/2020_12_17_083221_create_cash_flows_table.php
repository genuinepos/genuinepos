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
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('sender_account_id')->nullable();
            $table->unsignedBigInteger('receiver_account_id')->nullable();
            $table->unsignedBigInteger('purchase_payment_id')->nullable();
            $table->unsignedBigInteger('sale_payment_id')->nullable();
            $table->unsignedBigInteger('expanse_payment_id')->nullable();
            $table->decimal('debit', 22, 2)->nullable();
            $table->decimal('credit', 22, 2)->nullable();
            $table->decimal('balance', 22, 2)->default(0.00);
            $table->tinyInteger('transaction_type')->comment('1=payment;2=sale_payment;3=purchase_payment;4=fundTransfer;5=deposit;6=expansePayment;7=openingBalance');
            $table->tinyInteger('cash_type')->nullable()->comment('1=debit;2=credit;');
            $table->string('date');
            $table->string('month');
            $table->string('year');
            $table->timestamp('report_date');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('related_cash_flow_id')->nullable();
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('sender_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('receiver_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('purchase_payment_id')->references('id')->on('purchase_payments')->onDelete('cascade');
            $table->foreign('sale_payment_id')->references('id')->on('sale_payments')->onDelete('cascade');
            $table->foreign('expanse_payment_id')->references('id')->on('expanse_payments')->onDelete('cascade');
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
