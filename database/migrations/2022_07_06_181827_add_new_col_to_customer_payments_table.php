<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColToCustomerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_payments', function (Blueprint $table) {
            
            $table->string('reference')->after('voucher_no')->nullable();
            $table->decimal('less_amount', 22, 2)->after('paid_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_payments', function (Blueprint $table) {
            //
        });
    }
}
