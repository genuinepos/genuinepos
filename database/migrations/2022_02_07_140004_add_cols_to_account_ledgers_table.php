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
            $table->unsignedBigInteger('contra_receiver_id')->after('loan_payment_id')->nullable();
            $table->unsignedBigInteger('contra_sender_id')->after('contra_receiver_id')->nullable();
            $table->foreign('contra_receiver_id')->references('id')->on('contras')->onDelete('cascade');
            $table->foreign('contra_sender_id')->references('id')->on('contras')->onDelete('cascade');
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
