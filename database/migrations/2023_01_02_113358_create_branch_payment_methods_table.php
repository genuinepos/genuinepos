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
        Schema::create('branch_payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_method_id')->nullable()->index('branch_payment_methods_payment_method_id_foreign');
            $table->unsignedBigInteger('account_id')->nullable()->index('branch_payment_methods_account_id_foreign');
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
        Schema::dropIfExists('branch_payment_methods');
    }
};
