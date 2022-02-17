<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToCustomerLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_ledgers', function (Blueprint $table) {
            $table->string('voucher_type', 20)->nullable();
            $table->decimal('debit', 22, 2)->default(0);
            $table->decimal('credit', 22, 2)->default(0);
            $table->decimal('running_balance', 22, 2)->default(0);
            $table->string('amount_type', 20)->nullable()->comment('debit/credit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_ledgers', function (Blueprint $table) {
            //
        });
    }
}
