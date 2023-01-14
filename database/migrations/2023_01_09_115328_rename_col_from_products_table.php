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

            $table->dropForeign(['parent_category_id']);
            $table->renameColumn('parent_category_id', 'sub_category_id');
            $table->foreign('sub_category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');
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

            $table->dropForeign(['sub_category_id']);
            $table->renameColumn('sub_category_id', 'parent_category_id');
            $table->foreign('parent_category_id')
            	->references('id')
            	->on('categories')
            	->onDelete('set null');
        });
    }
};
