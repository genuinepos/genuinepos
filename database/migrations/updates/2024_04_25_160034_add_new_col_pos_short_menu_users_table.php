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
        Schema::table('short_menu_users', function (Blueprint $table) {

            if (!Schema::hasColumn('short_menu_users', 'screen_type')) {

                $table->tinyInteger('screen_type')->after('user_id')->default(1)->comment('1=dashboard_screen,2=pos_screen');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('short_menu_users', function (Blueprint $table) {

            $table->dropColumn('screen_type');
        });
    }
};
