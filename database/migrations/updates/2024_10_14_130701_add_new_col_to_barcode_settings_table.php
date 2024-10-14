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
        Schema::table('barcode_settings', function (Blueprint $table) {
            $table->string('right_margin', 191)->after('left_margin')->nullable()->comment('pixel');
            $table->string('left_margin', 191)->change()->nullable()->comment('pixel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barcode_settings', function (Blueprint $table) {
            //
        });
    }
};
