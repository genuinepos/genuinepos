<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToShortMenuUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('short_menu_users', function (Blueprint $table) {
            $table->foreign(['short_menu_id'])->references(['id'])->on('short_menus')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('short_menu_users', function (Blueprint $table) {
            $table->dropForeign('short_menu_users_short_menu_id_foreign');
            $table->dropForeign('short_menu_users_user_id_foreign');
        });
    }
}
