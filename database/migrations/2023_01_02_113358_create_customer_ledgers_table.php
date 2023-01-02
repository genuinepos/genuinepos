<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index('customer_ledgers_branch_id_foreign');
            $table->unsignedBigInteger('customer_id')->nullable()->index('customer_ledgers_customer_id_foreign');
            $table->unsignedBigInteger('sale_id')->nullable()->index('customer_ledgers_sale_id_foreign');
            $table->unsignedBigInteger('sale_return_id')->nullable()->index('customer_ledgers_sale_return_id_foreign');
            $table->unsignedBigInteger('sale_payment_id')->nullable()->index('customer_ledgers_sale_payment_id_foreign');
            $table->unsignedBigInteger('customer_payment_id')->nullable()->index('customer_ledgers_customer_payment_id_foreign');
            $table->unsignedBigInteger('money_receipt_id')->nullable()->index('customer_ledgers_money_receipt_id_foreign');
            $table->tinyInteger('row_type')->default(1)->comment('1=sale;2=sale_payment;3=opening_balance;4=money_receipt;5=supplier_payment');
            $table->decimal('amount', 22)->nullable()->comment('only_for_opening_balance');
            $table->string('date', 30)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->boolean('is_advanced')->default(false)->comment('only_for_money_receipt');
            $table->timestamps();
            $table->string('voucher_type', 20)->nullable();
            $table->decimal('debit', 22)->default(0);
            $table->decimal('credit', 22)->default(0);
            $table->decimal('running_balance', 22)->default(0);
            $table->string('amount_type', 20)->nullable()->comment('debit/credit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_ledgers');
    }
}
