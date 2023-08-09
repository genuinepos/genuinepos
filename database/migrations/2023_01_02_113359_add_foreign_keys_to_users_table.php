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
        Schema::table('users', function (Blueprint $table) {
            $table->foreign(['branch_id'], 'users_branch_id_foreign')->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['department_id'], 'users_department_id_foreign')->references(['id'])->on('hrm_department')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['designation_id'], 'users_designation_id_foreign')->references(['id'])->on('hrm_designations')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['shift_id'], 'users_shift_id_foreign')->references(['id'])->on('hrm_shifts')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_branch_id_foreign');
            $table->dropForeign('users_department_id_foreign');
            $table->dropForeign('users_designation_id_foreign');
            $table->dropForeign('users_shift_id_foreign');
        });
    }
};
