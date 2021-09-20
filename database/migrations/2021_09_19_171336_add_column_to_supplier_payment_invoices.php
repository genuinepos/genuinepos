<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToSupplierPaymentInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_payment_invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_return_id')->after('purchase_id')->nullable();
            $table->foreign('supplier_return_id')->references('id')->on('purchase_returns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_payments', function (Blueprint $table) {
            //
        });
    }
}
