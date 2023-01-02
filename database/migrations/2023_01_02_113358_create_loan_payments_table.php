<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_no')->nullable();
            $table->unsignedBigInteger('company_id')->nullable()->index('loan_payments_company_id_foreign');
            $table->unsignedBigInteger('branch_id')->nullable()->index('loan_payments_branch_id_foreign');
            $table->unsignedBigInteger('account_id')->nullable()->index('loan_payments_account_id_foreign');
            $table->decimal('paid_amount', 22)->default(0);
            $table->string('pay_mode')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable()->index('loan_payments_payment_method_id_foreign');
            $table->string('date')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('loan_payments_user_id_foreign');
            $table->tinyInteger('payment_type')->default(1)->comment('1=pay_loan_payment;2=get_loan_payment');
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
        Schema::dropIfExists('loan_payments');
    }
}
