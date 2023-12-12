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
        Schema::create('branch_settings', function (Blueprint $table) {
            $table->id();

            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('add_sale_invoice_layout_id')->nullable();
            $table->unsignedBigInteger('pos_sale_invoice_layout_id')->nullable();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('add_sale_invoice_layout_id')->references('id')->on('invoice_layouts')->onDelete('set null');
            $table->foreign('pos_sale_invoice_layout_id')->references('id')->on('invoice_layouts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_settings');
    }
};
