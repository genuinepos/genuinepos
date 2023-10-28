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
        Schema::create('payment_method_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_method_id')->nullable()->index('payment_method_settings_payment_method_id_foreign');
            $table->unsignedBigInteger('branch_id')->nullable()->index('payment_method_settings_branch_id_foreign');
            $table->unsignedBigInteger('account_id')->nullable()->index('payment_method_settings_account_id_foreign');
            $table->timestamps();

            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_method_settings');
    }
};
