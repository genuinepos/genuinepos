<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegisterTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_register_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_register_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->tinyInteger('cash_type')->default(2)->comment('1=debit;2=credit');
            $table->tinyInteger('transaction_type')->default(2)->comment('1=initial;2=sale');
            $table->decimal('amount', 22,2)->nullable();
            $table->timestamps();
            $table->foreign('cash_register_id')->references('id')->on('cash_registers')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_register_transactions');
    }
}
