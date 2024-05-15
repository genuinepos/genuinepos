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
        Schema::table('subscriptions', function (Blueprint $table) {

            if (!Schema::hasColumn('subscriptions', 'current_shop_count')) {

                $table->bigInteger('current_shop_count')->after('initial_shop_count')->nullable();
            }

            if (!Schema::hasColumn('subscriptions', 'is_completed_startup')) {

                $table->boolean('is_completed_startup')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('current_shop_count');
            $table->dropColumn('is_completed_startup');
        });
    }
};
