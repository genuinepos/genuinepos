<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCustomerPaymentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_payment_invoices', function (Blueprint $table) {
            $table->tinyInteger('type')->after('paid_amount')->nullable()->comment('1=sale_payment;2=sale_return_payment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_payment_invoices', function (Blueprint $table) {
            //
        });
    }
}
