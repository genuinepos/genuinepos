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
        Schema::table('hrm_payrolls', function (Blueprint $table) {
            $table->foreign(['admin_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
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
        Schema::table('hrm_payrolls', function (Blueprint $table) {
            $table->dropForeign('hrm_payrolls_admin_id_foreign');
            $table->dropForeign('hrm_payrolls_user_id_foreign');
        });
    }
};
