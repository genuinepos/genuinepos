<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('at_date');
            $table->unsignedBigInteger('user_id');
            $table->string('clock_in')->nullable();
            $table->string('clock_out')->nullable();
            $table->string('work_duration')->nullable();
            $table->text('clock_in_note')->nullable();
            $table->text('clock_out_note')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->timestamp('at_date_ts');
            $table->timestamp('clock_in_ts')->nullable();
            $table->timestamp('clock_out_ts')->nullable();
            $table->timestamp('is_completed')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('admin_and_users')->onDelete('cascade');
            $table->foreign('shift_id')->references('id')->on('hrm_shifts')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_attendances');
    }
}
