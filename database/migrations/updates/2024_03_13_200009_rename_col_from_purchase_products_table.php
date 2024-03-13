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
        Schema::table('purchase_products', function (Blueprint $table) {

            if (Schema::hasColumn('purchase_products', 'lebel_left_qty')) {

                $table->renameColumn('lebel_left_qty', 'label_left_qty');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_products', function (Blueprint $table) {

            $table->renameColumn('label_left_qty', 'lebel_left_qty');
        });
    }
};
