<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('voucher_no');
            $table->tinyInteger('voucher_type');
            $table->decimal('debit_total', 22, 2)->default(0);
            $table->decimal('credit_total', 22, 2)->default(0);
            $table->decimal('total_amount', 22, 2)->default(0);
            $table->string('date')->nullable();
            $table->timestamp('date_ts')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_vouchers');
    }
};
