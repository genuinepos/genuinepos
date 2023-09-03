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
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->dropForeign(['expense_id']);
            $table->dropForeign(['sale_payment_id']);
            $table->dropForeign(['supplier_payment_id']);
            $table->dropForeign(['purchase_payment_id']);
            $table->dropForeign(['customer_payment_id']);
            $table->dropForeign(['stock_adjustment_recover_id']);
            $table->dropForeign(['production_id']);
            $table->dropForeign(['contra_credit_id']);
            $table->dropForeign(['contra_debit_id']);

            $table->dropColumn('expense_id');
            $table->dropColumn('sale_payment_id');
            $table->dropColumn('supplier_payment_id');
            $table->dropColumn('purchase_payment_id');
            $table->dropColumn('customer_payment_id');
            $table->dropColumn('stock_adjustment_recover_id');
            $table->dropColumn('production_id');
            $table->dropColumn('contra_credit_id');
            $table->dropColumn('contra_debit_id');

            $table->unsignedBigInteger('purchase_product_id')->after('purchase_id')->nullable();
            $table->unsignedBigInteger('purchase_return_product_id')->after('purchase_return_id')->nullable();
            
            $table->foreign('purchase_product_id')->references('id')->on('purchase_products')->onDelete('cascade');
            $table->foreign('purchase_return_product_id')->references('id')->on('purchase_return_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            //
        });
    }
};
