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
        Schema::create('units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code_name');
            $table->unsignedBigInteger('base_unit_id')->after('code_name')->nullable();
            $table->decimal('base_unit_multiplier', 22, 4)->after('base_unit_id')->nullable();
            $table->unsignedBigInteger('created_by_id')->after('base_unit_multiplier')->nullable();
            $table->timestamp('deleted_at')->after('created_by_id')->nullable();
            $table->timestamps();

            $table->foreign('base_unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
};
