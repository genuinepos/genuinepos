<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index('account_ledgers_branch_id_foreign');
            $table->timestamp('date')->nullable();
            $table->string('voucher_type', 50)->nullable();
            $table->unsignedBigInteger('account_id')->nullable()->index('account_ledgers_account_id_foreign');
            $table->unsignedBigInteger('sale_id')->nullable()->index('account_ledgers_sale_id_foreign');
            $table->unsignedBigInteger('sale_return_id')->nullable()->index('account_ledgers_sale_return_id_foreign');
            $table->unsignedBigInteger('purchase_id')->nullable()->index('account_ledgers_purchase_id_foreign');
            $table->unsignedBigInteger('purchase_product_id')->nullable();
            $table->unsignedBigInteger('purchase_return_id')->nullable()->index('account_ledgers_purchase_return_id_foreign');
            $table->unsignedBigInteger('purchase_return_product_id')->nullable();
            $table->unsignedBigInteger('adjustment_id')->nullable()->index('account_ledgers_adjustment_id_foreign');
            $table->unsignedBigInteger('payroll_id')->nullable()->index('account_ledgers_payroll_id_foreign');
            $table->unsignedBigInteger('payroll_payment_id')->nullable()->index('account_ledgers_payroll_payment_id_foreign');
            $table->unsignedBigInteger('loan_id')->nullable()->index('account_ledgers_loan_id_foreign');
            $table->unsignedBigInteger('loan_payment_id')->nullable()->index('account_ledgers_loan_payment_id_foreign');
            $table->unsignedBigInteger('voucher_description_id')->nullable(0);
            $table->decimal('debit', 22)->default(0);
            $table->decimal('credit', 22)->default(0);
            $table->decimal('running_balance', 22)->default(0);
            $table->string('amount_type', 20)->nullable()->comment('debit/credit');
            $table->boolean('is_cash_flow')->default(false);
            $table->timestamps();

            $table->foreign('voucher_description_id')->references('id')->on('accounting_voucher_descriptions')->onDelete('cascade');
            $table->foreign('purchase_product_id')->references('id')->on('purchase_products')->onDelete('cascade');
            $table->foreign('purchase_return_product_id')->references('id')->on('purchase_return_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_ledgers');
    }
};
