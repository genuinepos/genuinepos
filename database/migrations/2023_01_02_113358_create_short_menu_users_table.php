<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortMenuUsersTable extends Migration
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
            $table->boolean('is_delete_in_update')->default(false);
            $table->timestamps();
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
}