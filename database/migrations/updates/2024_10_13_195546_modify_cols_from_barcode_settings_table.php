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
            $table->string('top_margin', 191)->change()->nullable()->comment('pixel');
            $table->string('paper_width', 191)->change()->nullable()->comment('mm');
            $table->string('paper_height', 191)->change()->nullable()->comment('mm');
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
