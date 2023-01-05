<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToWorkspaceTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workspace_tasks', function (Blueprint $table) {
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['workspace_id'])->references(['id'])->on('workspaces')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workspace_tasks', function (Blueprint $table) {
            $table->dropForeign('workspace_tasks_user_id_foreign');
            $table->dropForeign('workspace_tasks_workspace_id_foreign');
        });
    }
}
