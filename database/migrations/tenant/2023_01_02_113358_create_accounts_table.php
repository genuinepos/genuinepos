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
            $table->unsignedBigInteger('branch_id')->nullable()->index('accounts_branch_id_foreign');
            $table->unsignedBigInteger('account_group_id')->nullable();
            $table->integer('account_type')->default(2);
            $table->string('name');
            $table->string('phone', 255)->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->text('address')->nullable();
            $table->string('account_number')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable()->index('accounts_bank_id_foreign');
            $table->string('bank_branch', 255)->nullable();
            $table->text('bank_address')->nullable();
            $table->decimal('tax_percent', 22, 2)->nullable();
            $table->text('bank_code')->nullable();
            $table->text('swift_code')->nullable();
            $table->decimal('opening_balance', 22)->default(0);
            $table->string('opening_balance_type', 10)->nullable();
            $table->mediumText('remark')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->boolean('is_fixed')->nullable();
            $table->boolean('is_main_capital_account')->nullable();
            $table->boolean('is_main_pl_account')->nullable();
            $table->boolean('is_global')->default(false);
            $table->timestamps();

            $table->foreign('account_group_id')->references('id')->on('account_groups')->onDelete('cascade');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
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
