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
        Schema::table('stock_chains', function (Blueprint $table) {

            if (Schema::hasColumn('stock_chains', 'sold_qty')) {

                $table->renameColumn('sold_qty', 'out_qty');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_chains', function (Blueprint $table) {
            $table->renameColumn('out_qty', 'sold_qty');
        });
    }
};
