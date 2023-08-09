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
        Schema::table('sv_job_sheets_parts', function (Blueprint $table) {
            $table->foreign(['job_sheet_id'])->references(['id'])->on('sv_job_sheets')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_variant_id'])->references(['id'])->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sv_job_sheets_parts', function (Blueprint $table) {
            $table->dropForeign('sv_job_sheets_parts_job_sheet_id_foreign');
            $table->dropForeign('sv_job_sheets_parts_product_id_foreign');
            $table->dropForeign('sv_job_sheets_parts_product_variant_id_foreign');
        });
    }
};
