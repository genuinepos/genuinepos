<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgainEditMoneyReceiptsTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('money_receipts', function (Blueprint $table) {
            $table->string('receiver')->after('note')->nullable();
            $table->string('ac_details')->after('receiver')->nullable();
            $table->boolean('is_header_less')->after('ac_details')->default(0);
            $table->bigInteger('gap_from_top')->after('is_header_less')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('money_receipts', function (Blueprint $table) {
            //
        });
    }
}
