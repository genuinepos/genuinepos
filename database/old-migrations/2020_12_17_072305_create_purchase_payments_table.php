<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->string('pay_mode')->nullable();
            $table->decimal('paid_amount', 22, 2)->default(0.00);
            $table->tinyInteger('payment_on')->default(1)->comment('1=purchase_invoice_due;2=supplier_due');
            $table->tinyInteger('payment_type')->default(1)->comment('1=purchase_due;2=return_due');
            $table->tinyInteger('payment_status')->nullable()->comment('1=due;2=partial;3=paid');
            $table->string('date');
            $table->string('month');
            $table->string('year');
            $table->timestamp('report_date');
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
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_payments');
    }
}
