<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->unsignedBigInteger('expense_payment_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('sale_payment_id')->nullable();
            $table->unsignedBigInteger('sale_return_id')->nullable();
            $table->unsignedBigInteger('sale_return_payment_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('purchase_payment_id')->nullable();
            $table->unsignedBigInteger('purchase_return_id')->nullable();
            $table->unsignedBigInteger('purchase_return_payment_id')->nullable();
            $table->unsignedBigInteger('adjustment_id')->nullable();
            $table->unsignedBigInteger('payroll_id')->nullable();
            $table->unsignedBigInteger('payroll_payment_id')->nullable();
            $table->unsignedBigInteger('production_id')->nullable();
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('expense_id')->references('id')->on('expanses')->onDelete('cascade');
            $table->foreign('expense_payment_id')->references('id')->on('expanse_payments')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('sale_payment_id')->references('id')->on('sale_payments')->onDelete('cascade');
            $table->foreign('sale_return_id')->references('id')->on('sale_returns')->onDelete('cascade');
            $table->foreign('sale_return_payment_id')->references('id')->on('sale_payments')->onDelete('cascade');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('purchase_payment_id')->references('id')->on('purchase_payments')->onDelete('cascade');
            $table->foreign('purchase_return_id')->references('id')->on('purchase_returns')->onDelete('cascade');
            $table->foreign('purchase_return_payment_id')->references('id')->on('purchase_payments')->onDelete('cascade');
            $table->foreign('adjustment_id')->references('id')->on('stock_adjustments')->onDelete('cascade');
            $table->foreign('payroll_id')->references('id')->on('hrm_payrolls')->onDelete('cascade');
            $table->foreign('payroll_payment_id')->references('id')->on('hrm_payroll_payments')->onDelete('cascade');
            $table->foreign('production_id')->references('id')->on('productions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_transactions');
    }
}
