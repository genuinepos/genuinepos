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
        Schema::create('short_menu_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('short_menu_id')->nullable()->index('short_menu_users_short_menu_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('short_menu_users_user_id_foreign');
            $table->tinyInteger('screen_type')->default(1);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();

            $table->foreign(['short_menu_id'])->references(['id'])->on('short_menus')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('short_menu_users');
    }
};
