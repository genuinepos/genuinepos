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
            if(Schema::hasColumn('products', 'parent_category_id')) {
                $table->renameColumn('parent_category_id', 'sub_category_id');
                $table->renameIndex('products_parent_category_id_foreign', 'products_sub_category_id_foreign');
            }
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
            if(Schema::hasColumn('products', 'sub_category_id')) {
                $table->renameColumn('sub_category_id', 'parent_category_id');
                $table->renameIndex('products_sub_category_id_foreign', 'products_parent_category_id_foreign');
            }
        });
    }
};
