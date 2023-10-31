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
        Schema::create('workspace_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workspace_id')->index('workspace_tasks_workspace_id_foreign');
            $table->string('task_name');
            $table->unsignedBigInteger('user_id')->nullable()->index('workspace_tasks_user_id_foreign');
            $table->string('deadline')->nullable();
            $table->string('status')->nullable();
            $table->string('priority')->nullable();
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
        Schema::dropIfExists('workspace_tasks');
    }
};
