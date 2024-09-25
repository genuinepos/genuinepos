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
        Schema::table('invoice_layouts', function (Blueprint $table) {
            $table->boolean('customer_current_balance')->after('customer_phone')->default(1);
            $table->boolean('product_brand')->after('product_tax')->default(0);
            $table->boolean('product_details')->after('product_brand')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_layouts', function (Blueprint $table) {
            $table->dropColumn('customer_current_balance');
            $table->dropColumn('product_brand');
            $table->dropColumn('product_details');

        });
    }
};
