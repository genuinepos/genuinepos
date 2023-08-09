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
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('invoice_schema_id');
            $table->dropColumn('add_sale_invoice_layout_id');
            $table->dropColumn('pos_sale_invoice_layout_id');
            $table->dropColumn('default_account_id');
            $table->dropColumn('after_purchase_store');
            $table->unsignedBigInteger('parent_branch_id')->nullable();

            $table->foreign('parent_branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            //
        });
    }
};
