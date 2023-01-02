<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processes', function (Blueprint $table) {
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('processes', function (Blueprint $table) {
            $table->dropForeign('processes_branch_id_foreign');
            $table->dropForeign('processes_product_id_foreign');
            $table->dropForeign('processes_unit_id_foreign');
            $table->dropForeign('processes_variant_id_foreign');
        });
    }
}
