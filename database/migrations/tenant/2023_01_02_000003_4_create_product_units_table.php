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
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('base_unit_id');
            $table->decimal('base_unit_multiplier', 22, 2)->default(0);
            $table->decimal('assigned_unit_quantity', 22, 2)->default(0);
            $table->unsignedBigInteger('assigned_unit_id');
            $table->decimal('unit_cost_exc_tax', 22, 2)->default(0);
            $table->decimal('unit_cost_inc_tax', 22, 2)->default(0);
            $table->decimal('unit_price_exc_tax', 22, 2)->default(0);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('base_unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('assigned_unit_id')->references('id')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_units');
    }
};
