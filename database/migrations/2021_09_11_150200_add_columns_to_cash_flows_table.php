<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_flows', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_payment_id')->after('sale_payment_id')->nullable();
            $table->unsignedBigInteger('supplier_payment_id')->after('purchase_payment_id')->nullable();
            $table->foreign('customer_payment_id')->references('id')->on('customer_payments')->onDelete('cascade');
            $table->foreign('supplier_payment_id')->references('id')->on('supplier_payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_flows', function (Blueprint $table) {
            //
        });
    }
}
