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
        Schema::create('hrm_leave_types', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('name');
            $table->string('max_leave_count')->nullable();
            $table->integer('leave_count_interval');
            $table->timestamps();
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_leave_types');
    }
};
