<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id('id');
            $table->string('country', 100)->nullable();
            $table->string('currency', 100)->nullable();
            $table->string('code', 25)->nullable();
            $table->string('symbol', 25)->nullable();
            $table->string('thousand_separator', 10)->nullable();
            $table->string('decimal_separator', 10)->nullable();
            $table->string('dialing_code', 25)->nullable();
            $table->string('currency_rate', 25)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
