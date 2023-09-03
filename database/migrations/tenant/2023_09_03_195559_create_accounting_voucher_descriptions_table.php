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
        Schema::create('accounting_voucher_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accounting_voucher_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('transaction_no')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('cheque_serial_no')->nullable();
            $table->string('cheque_issue_date')->nullable();
            $table->string('note')->nullable();
            $table->string('amount_type')->nullable();
            $table->decimal('amount', 22, 2)->default();
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();

            $table->foreign('accounting_voucher_id')->references('id')->on('accounting_vouchers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_voucher_descriptions');
    }
};
