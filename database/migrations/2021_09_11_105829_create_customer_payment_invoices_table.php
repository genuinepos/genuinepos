<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerPaymentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payment_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_payment_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->decimal('paid_amount', 22)->default(0);
            $table->timestamps();
            $table->foreign('customer_payment_id')->references('id')->on('customer_payments')->onDelete('CASCADE');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_payment_invoices');
    }
}
