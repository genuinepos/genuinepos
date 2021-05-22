<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('account_number');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedBigInteger('account_type_id')->nullable();
            $table->decimal('opening_balance', 10, 2)->default(0.00);
            $table->decimal('debit', 22, 2)->default(0.00);
            $table->decimal('credit', 22, 2)->default(0.00);
            $table->decimal('balance', 22, 2)->default(0.00);
            $table->mediumText('remark')->nullable();
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
            $table->foreign('account_type_id')->references('id')->on('account_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
