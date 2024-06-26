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

            if (Schema::hasColumn('shop_expire_date_histories', 'shop_count')) {

                $table->dropColumn('shop_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_expire_date_histories', function (Blueprint $table) {

            $table->bigInteger('shop_count')->after('adjustable_price');
        });
    }
};
