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

            DB::getDoctrineSchemaManager()->getDatabasePlatform()->markDoctrineTypeCommented(Type::getType('string'));

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
