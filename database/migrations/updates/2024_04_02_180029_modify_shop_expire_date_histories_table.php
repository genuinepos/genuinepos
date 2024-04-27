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
        Schema::table('shop_expire_date_histories', function (Blueprint $table) {

            if (Schema::hasColumn('shop_expire_date_histories', 'created_count')) {

                $table->dropColumn('created_count');
            }

            if (Schema::hasColumn('shop_expire_date_histories', 'left_count')) {

                $table->dropColumn('left_count');
            }

            if (!Schema::hasColumn('shop_expire_date_histories', 'is_created')) {

                $table->boolean('is_created')->after('expire_date')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_expire_date_histories', function (Blueprint $table) {

            $table->bigInteger('created_count')->after('expire_date');
            $table->bigInteger('left_count')->after('created_count');
            $table->dropColumn('is_created');
        });
    }
};
