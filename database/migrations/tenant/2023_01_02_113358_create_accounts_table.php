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
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_type')->default(2);
            $table->string('name');
            $table->string('account_number')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable()->index('accounts_bank_id_foreign');
            $table->decimal('opening_balance', 22)->default(0);
            $table->decimal('debit', 22)->default(0);
            $table->decimal('credit', 22)->default(0);
            $table->decimal('balance', 22)->default(0);
            $table->mediumText('remark')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('branch_id')->nullable()->index('accounts_branch_id_foreign');
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
};
