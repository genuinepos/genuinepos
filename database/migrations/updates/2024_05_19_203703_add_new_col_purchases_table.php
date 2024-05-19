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

            if (!Schema::hasColumn('purchases', 'purchase_order_id')) {

                $table->unsignedBigInteger('purchase_order_id')->after('po_receiving_status')->nullable();
                $table->foreign('purchase_order_id')->references('id')->on('purchases')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['purchase_order_id']);
            $table->dropColumn('purchase_order_id');
        });
    }
};
