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
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_no')->nullable();
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable()->index('customer_payments_branch_id_foreign');
            $table->unsignedBigInteger('customer_id')->nullable()->index('customer_payments_customer_id_foreign');
            $table->unsignedBigInteger('account_id')->nullable()->index('customer_payments_account_id_foreign');
            $table->decimal('paid_amount', 22)->default(0);
            $table->decimal('less_amount', 22)->default(0);
            $table->timestamp('report_date')->nullable();
            $table->tinyInteger('type')->default(1);
            $table->string('pay_mode')->nullable();
            $table->string('date');
            $table->string('time');
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
            $table->text('note')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable()->index('customer_payments_admin_id_foreign');
            $table->timestamps();
            $table->unsignedBigInteger('payment_method_id')->nullable()->index('customer_payments_payment_method_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_payments');
    }
};
