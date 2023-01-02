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
            $table->bigIncrements('id');
            $table->string('reference_number')->nullable();
            $table->unsignedBigInteger('leave_id')->index('hrm_leaves_leave_id_foreign');
            $table->unsignedBigInteger('employee_id')->index('hrm_leaves_employee_id_foreign');
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->text('reason')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('hrm_leaves');
    }
}
