<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierPaymentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_payment_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_payment_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->decimal('paid_amount', 22)->default(0);
            $table->timestamps();
            $table->foreign('supplier_payment_id')->references('id')->on('supplier_payments')->onDelete('CASCADE');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_payment_invoices');
    }
}
