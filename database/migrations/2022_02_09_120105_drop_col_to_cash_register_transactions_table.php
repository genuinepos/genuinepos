<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColToCashRegisterTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_register_transactions', function (Blueprint $table) {
            $table->dropColumn('cash_type');
            $table->dropColumn('transaction_type');
            $table->dropColumn('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_register_transactions', function (Blueprint $table) {
            //
        });
    }
}
