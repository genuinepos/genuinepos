<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceSchemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_schemas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('format')->nullable();
            $table->string('start_fr0m')->nullable();
            $table->tinyInteger('number_of_digit')->nullable();
            $table->string('prefix')->nullable();
            $table->boolean('is_default')->default(0);
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
        Schema::dropIfExists('invoice_schemas');
    }
}
