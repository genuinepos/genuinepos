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
        Schema::create('hrm_payroll_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_no')->nullable();
            $table->unsignedBigInteger('payroll_id')->nullable()->index('hrm_payroll_payments_payroll_id_foreign');
            $table->unsignedBigInteger('account_id')->nullable()->index('hrm_payroll_payments_account_id_foreign');
            $table->decimal('paid', 22)->default(0);
            $table->decimal('due', 22)->default(0);
            $table->string('pay_mode')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable()->index('hrm_payroll_payments_payment_method_id_foreign');
            $table->string('date');
            $table->string('time', 50)->nullable();
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
            $table->unsignedBigInteger('admin_id')->nullable()->index('hrm_payroll_payments_admin_id_foreign');
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
        Schema::dropIfExists('hrm_payroll_payments');
    }
};
