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
        Schema::create('todo_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('todo_id')->index('todo_users_todo_id_foreign');
            $table->unsignedBigInteger('user_id')->index('todo_users_user_id_foreign');
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();

            $table->foreign(['todo_id'])->references(['id'])->on('todos')->onDelete('CASCADE');
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
        Schema::dropIfExists('todo_users');
    }
};
