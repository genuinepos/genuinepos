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
        Schema::table('process_ingredients', function (Blueprint $table) {
            $table->foreign(['process_id'])->references(['id'])->on('processes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('process_ingredients', function (Blueprint $table) {
            $table->dropForeign('process_ingredients_process_id_foreign');
            $table->dropForeign('process_ingredients_product_id_foreign');
            $table->dropForeign('process_ingredients_unit_id_foreign');
            $table->dropForeign('process_ingredients_variant_id_foreign');
        });
    }
};
