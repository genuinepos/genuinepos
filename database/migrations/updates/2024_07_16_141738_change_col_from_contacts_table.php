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
        Schema::table('contacts', function (Blueprint $table) {

            if (Schema::hasColumn('contacts', 'pay_term_number')) {

                $table->bigInteger('pay_term_number')->change()->nullable()->default(null);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {

            $table->tinyInteger('pay_term_number')->change()->nullable()->default(null);
        });
    }
};
