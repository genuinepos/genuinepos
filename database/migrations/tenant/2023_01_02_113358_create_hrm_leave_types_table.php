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
        Schema::create('hrm_leavetypes', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('leave_type');
            $table->integer('max_leave_count');
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
        Schema::dropIfExists('hrm_leavetypes');
    }
};
