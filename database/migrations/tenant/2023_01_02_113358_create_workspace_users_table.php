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
        Schema::create('workspace_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workspace_id')->nullable()->index('workspace_users_workspace_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('workspace_users_user_id_foreign');
            $table->boolean('is_delete_in_update')->default(false);
            $table->timestamps();

            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['workspace_id'])->references(['id'])->on('workspaces')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workspace_users');
    }
};
