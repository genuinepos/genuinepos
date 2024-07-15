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
        Schema::table('short_menus', function (Blueprint $table) {

            if (!Schema::hasColumn('short_menus', 'plan_feature')) {

                $table->string('plan_feature', 50)->after('permission')->nullable();
            }

            if (!Schema::hasColumn('short_menus', 'enable_module')) {

                $table->string('enable_module', 50)->after('plan_feature')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('short_menus', function (Blueprint $table) {
            $table->dropColumn('plan_feature');
            $table->dropColumn('enable_module');
        });
    }
};
