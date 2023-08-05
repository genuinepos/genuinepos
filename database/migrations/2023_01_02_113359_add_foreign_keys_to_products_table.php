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
        Schema::table('products', function (Blueprint $table) {
            $table->foreign(['brand_id'])->references(['id'])->on('brands')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['category_id'])->references(['id'])->on('categories')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['sub_category_id'])->references(['id'])->on('categories')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['tax_id'])->references(['id'])->on('taxes')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['warranty_id'])->references(['id'])->on('warranties')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_brand_id_foreign');
            $table->dropForeign('products_category_id_foreign');
            $table->dropForeign('products_sub_category_id_foreign');
            $table->dropForeign('products_tax_id_foreign');
            $table->dropForeign('products_unit_id_foreign');
            $table->dropForeign('products_warranty_id_foreign');
        });
    }
};
