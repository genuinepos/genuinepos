<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->decimal('paid_amount', 22)->default(0);
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
			$table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('CASCADE');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('CASCADE');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('CASCADE');
            $table->foreign('admin_id')->references('id')->on('admin_and_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_payments');
    }
}
