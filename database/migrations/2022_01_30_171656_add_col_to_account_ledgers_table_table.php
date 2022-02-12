<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToAccountLedgersTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_adjustment_recover_id')->after('adjustment_id')->nullable();
            $table->foreign('stock_adjustment_recover_id')->references('id')->on('stock_adjustment_recovers')->onDelete('cascade');
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
