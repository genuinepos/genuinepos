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
        Schema::table('hrm_leaves', function (Blueprint $table) {

            $table->string('leave_no', 191)->after('id')->nullable();
            $table->unsignedBigInteger('leave_type_id')->after('leave_no')->nullable();
            $table->foreign(['leave_type_id'])->references(['id'])->on('hrm_leavetypes')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrm_leaves', function (Blueprint $table) {
            //
        });
    }
};
