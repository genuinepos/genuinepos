<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $foreignKeys = [
                'products_warranty_id_foreign',
                'products_category_id_foreign',
                'products_sub_category_id_foreign',
                'products_brand_id_foreign',
                'products_unit_id_foreign',
            ];

            foreach ($foreignKeys as $foreignKey) {

                DB::statement("ALTER TABLE `products` DROP INDEX `$foreignKey`");
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

        });
    }
};
