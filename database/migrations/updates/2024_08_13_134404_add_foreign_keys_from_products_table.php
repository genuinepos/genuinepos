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

            // $table->foreign('tax_ac_id')->references('id')->on('accounts')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign('sub_category_id')->references('id')->on('categories')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign('brand_id')->references('id')->on('brands')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign('unit_id')->references('id')->on('units')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign('warranty_id')->references('id')->on('warranties')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
