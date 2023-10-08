<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_ingredients', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('process_id')->index('process_ingredients_process_id_foreign');
            $table->unsignedBigInteger('product_id')->index('process_ingredients_product_id_foreign');
            $table->unsignedBigInteger('variant_id')->nullable()->index('process_ingredients_variant_id_foreign');
            $table->decimal('wastage_percent', 22, 2)->default(0);
            $table->decimal('wastage_amount', 22, 2)->default(0);
            $table->decimal('final_qty', 22, 2)->default(0);
            $table->unsignedBigInteger('unit_id')->nullable()->index('process_ingredients_unit_id_foreign');
            $table->decimal('unit_cost_exc_tax', 22, 2)->default(0);
            $table->unsignedBigInteger('tax_ac_id')->nullable();
            $table->tinyInteger('unit_tax_type')->default(1);
            $table->decimal('unit_tax_percent', 22, 2)->default(0);
            $table->decimal('unit_tax_amount', 22, 2)->default(0);
            $table->decimal('unit_cost_inc_tax', 22, 2)->default(0);
            $table->decimal('subtotal', 22, 2)->default(0);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();

            $table->foreign(['process_id'])->references(['id'])->on('processes')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('CASCADE');
            $table->foreign(['tax_ac_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_ingredients');
    }
};
