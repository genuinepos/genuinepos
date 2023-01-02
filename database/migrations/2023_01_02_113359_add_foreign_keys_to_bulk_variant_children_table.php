<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBulkVariantChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bulk_variant_children', function (Blueprint $table) {
            $table->foreign(['bulk_variant_id'])->references(['id'])->on('bulk_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bulk_variant_children', function (Blueprint $table) {
            $table->dropForeign('bulk_variant_children_bulk_variant_id_foreign');
        });
    }
}
