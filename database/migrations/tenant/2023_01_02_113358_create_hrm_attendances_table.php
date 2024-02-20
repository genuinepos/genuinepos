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
        Schema::create('hrm_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->index('hrm_attendances_branch_id_foreign')->nullable();
            $table->string('clock_in_date');
            $table->string('clock_out_date')->nullable();
            $table->unsignedBigInteger('user_id')->index('hrm_attendances_user_id_foreign');
            $table->string('clock_in')->nullable();
            $table->string('clock_out')->nullable();
            $table->string('work_duration')->nullable();
            $table->text('clock_in_note')->nullable();
            $table->text('clock_out_note')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->timestamp('clock_in_ts')->nullable();
            $table->timestamp('clock_out_ts')->nullable();
            $table->timestamp('at_date_ts')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->timestamps();

            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['shift_id'])->references(['id'])->on('hrm_shifts')->onDelete('SET NULL');
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
};
