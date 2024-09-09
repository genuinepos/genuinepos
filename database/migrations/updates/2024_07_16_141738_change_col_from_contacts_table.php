<?php

use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {

            if (Schema::hasColumn('contacts', 'pay_term_number')) {

                DB::statement('ALTER TABLE `contacts` MODIFY `pay_term_number` BIGINT NULL DEFAULT NULL');
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
