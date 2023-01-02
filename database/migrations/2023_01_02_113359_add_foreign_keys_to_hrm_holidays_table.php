<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToHrmHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrm_holidays', function (Blueprint $table) {
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrm_holidays', function (Blueprint $table) {
            $table->dropForeign('hrm_holidays_branch_id_foreign');
        });
    }
}
