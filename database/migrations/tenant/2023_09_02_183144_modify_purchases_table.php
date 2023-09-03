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
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
            $table->dropColumn('purchase_tax_id');

            $table->unsignedBigInteger('supplier_account_id')->after('branch_id')->nullable();
            $table->unsignedBigInteger('purchase_tax_ac_id')->after('purchase_note')->nullable();

            $table->foreign('supplier_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('purchase_tax_ac_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
        });
    }
};
