<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index('sale_payments_branch_id_foreign');
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable()->index('sale_payments_sale_id_foreign');
            $table->unsignedBigInteger('sale_return_id')->nullable()->index('sale_payments_sale_return_id_foreign');
            $table->unsignedBigInteger('customer_payment_id')->nullable()->index('sale_payments_customer_payment_id_foreign');
            $table->unsignedBigInteger('customer_id')->nullable()->index('sale_payments_customer_id_foreign');
            $table->unsignedBigInteger('account_id')->nullable()->index('sale_payments_account_id_foreign');
            $table->string('pay_mode')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable()->index('sale_payments_payment_method_id_foreign');
            $table->decimal('paid_amount', 22)->default(0);
            $table->tinyInteger('payment_on')->default(1)->comment('1=sale_invoice_due;2=customer_due');
            $table->tinyInteger('payment_type')->default(1)->comment('1=sale_due;2=return_due');
            $table->tinyInteger('payment_status')->nullable()->comment('1=due;2=partial;3=paid');
            $table->string('date');
            $table->string('time');
            $table->string('month');
            $table->string('year');
            $table->timestamp('report_date')->useCurrentOnUpdate()->useCurrent();
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
            $table->mediumText('note')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
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
        Schema::dropIfExists('sale_payments');
    }
}
