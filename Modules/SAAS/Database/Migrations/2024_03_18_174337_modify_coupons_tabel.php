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
        Schema::table('coupons', function (Blueprint $table) {

            $table->dropColumn('purchase_price');
            $table->decimal('minimum_purchase_amount', 22, 2)->after('is_minimum_purchase')->default(0);
            $table->boolean('is_minimum_purchase')->change()->default(0);
            $table->boolean('is_maximum_usage')->change()->default(0);
            $table->bigInteger('no_of_usage')->change()->default(0);

            if (Schema::hasColumn('coupons', 'no_of_used')) {

                $table->integer('no_of_used')->default(0);
            }

            if (Schema::hasColumn('coupons', 'no_of_used')) {

                $table->bigInteger('no_of_used')->change()->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {

        });
    }
};
