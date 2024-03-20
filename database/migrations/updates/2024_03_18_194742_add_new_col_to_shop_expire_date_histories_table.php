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

            if (!Schema::hasColumn('shop_expire_date_histories', 'current_price') && !Schema::hasColumn('shop_expire_date_histories', 'adjustable_price')) {

                $table->decimal('current_price', 22, 2)->after('price_period')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_expire_date_histories', function (Blueprint $table) {
            $table->dropColumn('current_price');
        });
    }
};
