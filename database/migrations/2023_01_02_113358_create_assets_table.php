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
        Schema::create('assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('asset_name');
            $table->unsignedBigInteger('type_id')->nullable()->index('assets_type_id_foreign');
            $table->unsignedBigInteger('branch_id')->nullable()->index('assets_branch_id_foreign');
            $table->decimal('quantity', 22)->default(0);
            $table->decimal('per_unit_value', 22)->default(0);
            $table->decimal('total_value', 22)->default(0);
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['type_id'])->references(['id'])->on('asset_types')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
};
