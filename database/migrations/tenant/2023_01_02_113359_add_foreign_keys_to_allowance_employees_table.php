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
        Schema::table('allowance_employees', function (Blueprint $table) {
            $table->foreign(['allowance_id'])->references(['id'])->on('hrm_allowance')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('allowance_employees', function (Blueprint $table) {
            $table->dropForeign('allowance_employees_allowance_id_foreign');
            $table->dropForeign('allowance_employees_user_id_foreign');
        });
    }
};
