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
        Schema::create('bulk_variant_children', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bulk_variant_id')->nullable()->index('bulk_variant_children_bulk_variant_id_foreign');
            $table->string('name', 255)->nullable();
            $table->boolean('is_delete_in_update')->default(false);
            $table->timestamps();

            $table->foreign(['bulk_variant_id'])->references(['id'])->on('bulk_variants')->onDelete('CASCADE');
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
};
