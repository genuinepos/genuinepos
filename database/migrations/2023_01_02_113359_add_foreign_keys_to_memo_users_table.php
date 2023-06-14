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
        Schema::table('memo_users', function (Blueprint $table) {
            $table->foreign(['memo_id'])->references(['id'])->on('memos')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('memo_users', function (Blueprint $table) {
            $table->dropForeign('memo_users_memo_id_foreign');
            $table->dropForeign('memo_users_user_id_foreign');
        });
    }
};
