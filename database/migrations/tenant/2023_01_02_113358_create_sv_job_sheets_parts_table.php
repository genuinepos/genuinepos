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
        Schema::create('sv_job_sheets_parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_sheet_id')->index('sv_job_sheets_parts_job_sheet_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->index('sv_job_sheets_parts_product_id_foreign');
            $table->unsignedBigInteger('product_variant_id')->nullable()->index('sv_job_sheets_parts_product_variant_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sv_job_sheets_parts');
    }
};
