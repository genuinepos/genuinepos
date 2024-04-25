<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pos_short_menu_users', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('pos_short_menu_users');
            Schema::enableForeignKeyConstraints();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_short_menu_users', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('short_menu_id')->nullable()->index('pos_short_menu_users_short_menu_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('pos_short_menu_users_user_id_foreign');
            $table->boolean('is_delete_in_update')->default(false);
            $table->timestamps();

            $table->foreign(['short_menu_id'])->references(['id'])->on('short_menus')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }
};
