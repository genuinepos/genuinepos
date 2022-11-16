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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('users'); // laravel provided users table
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
            $table->dropForeign('users_role_permission_id_foreign');
            $table->dropColumn('role_id');
            $table->dropColumn('role_permission_id');
        });
        Schema::rename('admin_and_users', 'users');

        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('model_has_permissions');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        echo "roles\role_permissions\Tables were here\n";
    }
};
