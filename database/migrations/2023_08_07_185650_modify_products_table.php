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
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['tax_id']);
            $table->dropColumn('tax_id');
            $table->unsignedBigInteger('tax_ac_id')->after('unit_id')->nullable();
            $table->foreign('tax_ac_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['tax_ac_id']);
            $table->dropColumn('tax_ac_id');
            $table->unsignedBigInteger('tax_id')->after('unit_id')->nullable();
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('cascade');
        });
    }
};
