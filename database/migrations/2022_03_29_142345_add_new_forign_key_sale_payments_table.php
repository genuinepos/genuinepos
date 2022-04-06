<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewForignKeySalePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_payments', function (Blueprint $table) {
             
            $table->unsignedBigInteger('sale_return_id')->after('sale_id')->nullable();
            $table->foreign('sale_return_id')->references('id')->on('sale_returns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_payments', function (Blueprint $table) {
            //
        });
    }
}
