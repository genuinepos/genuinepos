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
        Schema::create('memo_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('memo_id')->index('memo_users_memo_id_foreign');
            $table->unsignedBigInteger('user_id')->index('memo_users_user_id_foreign');
            $table->boolean('is_delete_in_update')->default(false);
            $table->boolean('is_author')->default(false);
            $table->timestamps();

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
        Schema::dropIfExists('memo_users');
    }
};
