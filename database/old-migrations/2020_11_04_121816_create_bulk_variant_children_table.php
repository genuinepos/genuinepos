<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkVariantChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_variant_children', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bulk_variant_id')->nullable();
            $table->string('child_name')->nullable();
            $table->boolean('delete_in_update')->default(0);
            $table->timestamps();
            $table->foreign('bulk_variant_id')->references('id')->on('bulk_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bulk_variant_children');
    }
}
