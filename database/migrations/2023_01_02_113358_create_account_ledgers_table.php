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
            $table->unsignedBigInteger('expense_id')->nullable()->index('account_ledgers_expense_id_foreign');
            $table->unsignedBigInteger('expense_payment_id')->nullable()->index('account_ledgers_expense_payment_id_foreign');
            $table->unsignedBigInteger('sale_id')->nullable()->index('account_ledgers_sale_id_foreign');
            $table->unsignedBigInteger('sale_payment_id')->nullable()->index('account_ledgers_sale_payment_id_foreign');
            $table->unsignedBigInteger('supplier_payment_id')->nullable()->index('account_ledgers_supplier_payment_id_foreign');
            $table->unsignedBigInteger('sale_return_id')->nullable()->index('account_ledgers_sale_return_id_foreign');
            $table->unsignedBigInteger('purchase_id')->nullable()->index('account_ledgers_purchase_id_foreign');
            $table->unsignedBigInteger('purchase_payment_id')->nullable()->index('account_ledgers_purchase_payment_id_foreign');
            $table->unsignedBigInteger('customer_payment_id')->nullable()->index('account_ledgers_customer_payment_id_foreign');
            $table->unsignedBigInteger('purchase_return_id')->nullable()->index('account_ledgers_purchase_return_id_foreign');
            $table->unsignedBigInteger('adjustment_id')->nullable()->index('account_ledgers_adjustment_id_foreign');
            $table->unsignedBigInteger('stock_adjustment_recover_id')->nullable()->index('account_ledgers_stock_adjustment_recover_id_foreign');
            $table->unsignedBigInteger('payroll_id')->nullable()->index('account_ledgers_payroll_id_foreign');
            $table->unsignedBigInteger('payroll_payment_id')->nullable()->index('account_ledgers_payroll_payment_id_foreign');
            $table->unsignedBigInteger('production_id')->nullable()->index('account_ledgers_production_id_foreign');
            $table->unsignedBigInteger('loan_id')->nullable()->index('account_ledgers_loan_id_foreign');
            $table->unsignedBigInteger('loan_payment_id')->nullable()->index('account_ledgers_loan_payment_id_foreign');
            $table->unsignedBigInteger('contra_credit_id')->nullable()->index('account_ledgers_contra_credit_id_foreign');
            $table->unsignedBigInteger('contra_debit_id')->nullable()->index('account_ledgers_contra_debit_id_foreign');
            $table->decimal('debit', 22)->default(0);
            $table->decimal('credit', 22)->default(0);
            $table->decimal('running_balance', 22)->default(0);
            $table->string('amount_type', 20)->nullable()->comment('debit/credit');
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
        Schema::dropIfExists('account_ledgers');
    }
};
