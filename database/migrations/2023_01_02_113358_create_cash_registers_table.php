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
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_account_id')->nullable()->index('cash_registers_sale_account_id_foreign');
            $table->unsignedBigInteger('cash_counter_id')->nullable()->index('cash_registers_cash_counter_id_foreign');
            $table->unsignedBigInteger('branch_id')->nullable()->index('cash_registers_branch_id_foreign');
            $table->unsignedBigInteger('admin_id')->nullable()->index('cash_registers_admin_id_foreign');
            $table->decimal('cash_in_hand', 22)->default(0);
            $table->string('date', 20)->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->decimal('closed_amount', 22)->default(0);
            $table->boolean('status')->default(true)->comment('1=open;0=closed;');
            $table->text('closing_note')->nullable();
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
        Schema::dropIfExists('cash_registers');
    }
};
