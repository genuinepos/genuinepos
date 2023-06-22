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
        Schema::create('hrm_leaves', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('leave_no', 191)->nullable();
            $table->unsignedBigInteger('leave_type_id')->nullable();
            $table->unsignedBigInteger('employee_id')->index('hrm_leaves_employee_id_foreign');
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->text('reason')->nullable();
            $table->integer('status');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('hrm_leavetypes')->onDelete('SET NULL');
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
};
;
