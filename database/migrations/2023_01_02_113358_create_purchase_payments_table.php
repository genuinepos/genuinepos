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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index('purchase_payments_branch_id_foreign');
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable()->index('purchase_payments_purchase_id_foreign');
            $table->unsignedBigInteger('supplier_return_id')->nullable()->index('purchase_payments_supplier_return_id_foreign')->comment('only_for_supplier_return_payments');
            $table->unsignedBigInteger('supplier_id')->nullable()->index('purchase_payments_supplier_id_foreign');
            $table->unsignedBigInteger('supplier_payment_id')->nullable()->index('purchase_payments_supplier_payment_id_foreign');
            $table->unsignedBigInteger('account_id')->nullable()->index('purchase_payments_account_id_foreign');
            $table->string('pay_mode')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable()->index('purchase_payments_payment_method_id_foreign');
            $table->decimal('paid_amount', 22)->default(0);
            $table->tinyInteger('payment_on')->default(1)->comment('1=purchase_invoice_due;2=supplier_due');
            $table->tinyInteger('payment_type')->default(1)->comment('1=purchase_due;2=return_due');
            $table->tinyInteger('payment_status')->nullable()->comment('1=due;2=partial;3=paid');
            $table->string('date');
            $table->string('time', 191)->nullable();
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
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->mediumText('note')->nullable();
            $table->string('attachment', 191)->nullable();
            $table->timestamps();
            $table->boolean('is_advanced')->default(false);
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
