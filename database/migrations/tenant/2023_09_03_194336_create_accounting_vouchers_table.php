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
            $table->string('voucher_no');
            $table->tinyInteger('voucher_type');
            $table->tinyInteger('mode')->default(1)->comment('1=single_mode,2=multiple_mode');
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('sale_ref_id')->nullable();
            $table->unsignedBigInteger('sale_return_ref_id')->nullable();
            $table->unsignedBigInteger('purchase_ref_id')->nullable();
            $table->unsignedBigInteger('purchase_return_ref_id')->nullable();
            $table->unsignedBigInteger('stock_adjustment_ref_id')->nullable();
            $table->unsignedBigInteger('payroll_ref_id')->nullable();
            $table->decimal('debit_total', 22, 2)->default(0);
            $table->decimal('credit_total', 22, 2)->default(0);
            $table->decimal('total_amount', 22, 2)->default(0);
            $table->string('date')->nullable();
            $table->timestamp('date_ts')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('is_transaction_details')->default(0);
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('sale_ref_id')->references('id')->on('sales')->onDelete('set null');
            $table->foreign('sale_return_ref_id')->references('id')->on('sale_returns')->onDelete('set null');
            $table->foreign('purchase_ref_id')->references('id')->on('purchases')->onDelete('set null');
            $table->foreign('purchase_return_ref_id')->references('id')->on('purchase_returns')->onDelete('set null');
            $table->foreign('stock_adjustment_ref_id')->references('id')->on('stock_adjustments')->onDelete('set null');
            $table->foreign('payroll_ref_id')->references('id')->on('hrm_payrolls')->onDelete('set null');
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
