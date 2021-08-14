<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memo_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('memo_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            $table->foreign('memo_id')->references('id')->on('memos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('admin_and_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memo_users');
    }
}
