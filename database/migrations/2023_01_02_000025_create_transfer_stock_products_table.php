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
        Schema::create('transfer_stock_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_stock_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->decimal('unit_cost_inc_tax', 22, 2)->default(0);
            $table->decimal('subtotal', 22, 2)->default(0);
            $table->decimal('send_qty', 22, 2)->default(0);
            $table->decimal('received_qty', 22, 2)->default(0);
            $table->decimal('pending_qty', 22, 2)->default(0);
            $table->boolean('is_delete_in_update', 22, 2)->default(0);
            $table->timestamps();

            $table->foreign(['transfer_stock_id'])->references(['id'])->on('transfer_stocks')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_stock_products');
    }
};
