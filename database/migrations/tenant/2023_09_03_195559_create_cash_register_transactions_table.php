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
        Schema::create('cash_register_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cash_register_id')->nullable()->index('cash_register_transactions_cash_register_id_foreign');
            $table->unsignedBigInteger('sale_id')->nullable()->index('cash_register_transactions_sale_id_foreign');
            $table->unsignedBigInteger('voucher_description_id')->nullable()->index('cash_register_transactions_voucher_description_id_foreign');
            $table->timestamps();

            $table->foreign(['cash_register_id'])->references(['id'])->on('cash_registers')->onDelete('CASCADE');
            $table->foreign(['sale_id'])->references(['id'])->on('sales')->onDelete('CASCADE');
            $table->foreign(['voucher_description_id'])->references(['id'])->on('accounting_voucher_descriptions')->onDelete('CASCADE');
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
};
