<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_ingredients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('input_qty', 22)->default(0);
            $table->decimal('wastage_percent', 22)->default(0);
            $table->decimal('final_qty', 22)->default(0);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->decimal('unit_cost_inc_tax')->default(0);
            $table->decimal('subtotal', 22)->default(0);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            $table->foreign('production_id')->references('id')->on('processes')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_ingredients');
    }
}
