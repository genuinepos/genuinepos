<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->nullable();
            $table->unsignedBigInteger('leave_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->text('reason')->nullable();
            $table->integer('status')->defaul(0);
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('admin_and_users')->onDelete('cascade');
            $table->foreign('leave_id')->references('id')->on('hrm_leavetypes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_leaves');
    }
}
