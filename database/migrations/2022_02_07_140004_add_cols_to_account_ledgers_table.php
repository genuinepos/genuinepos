<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsToAccountLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->unsignedBigInteger('contra_credit_id')->after('loan_payment_id')->nullable();
            $table->unsignedBigInteger('contra_debit_id')->after('contra_credit_id')->nullable();
            $table->foreign('contra_credit_id')->references('id')->on('contras')->onDelete('cascade');
            $table->foreign('contra_debit_id')->references('id')->on('contras')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            //
        });
    }
}
