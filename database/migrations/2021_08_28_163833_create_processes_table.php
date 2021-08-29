<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('wastage_percent')->default(0);
            $table->decimal('total_output_qty', 22)->default(0);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->decimal('total_ingredient_cost', 22)->nullable();
            $table->decimal('production_cost', 22)->default(0);
            $table->decimal('total_cost', 22)->default(0);
            $table->text('process_instruction')->nullable();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processes');
    }
}
