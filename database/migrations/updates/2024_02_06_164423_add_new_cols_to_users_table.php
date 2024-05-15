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
        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'currency_id', 'city', 'postal_code')) {

                if (!Schema::hasColumn('users', 'currency_id')) {

                    $table->unsignedBigInteger('currency_id')->after('id_proof_number')->nullable();
                }

                if (!Schema::hasColumn('users', 'city')) {

                    $table->string('city')->after('currency_id')->nullable();
                }

                if (!Schema::hasColumn('users', 'postal_code')) {
                    
                    $table->string('postal_code')->after('city')->nullable();
                }

                $doctrineTable = Schema::getConnection()->getDoctrineSchemaManager()->listTableDetails('users');

                $hasForeignKey = $doctrineTable->hasForeignKey('users_currency_id_foreign');
                if (!isset($hasForeignKey)) {

                    $table->foreign(['currency_id'])->references(['id'])->on('currencies')->onDelete('set null');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['currency_id']);
            $table->dropColumn('currency_id');
            $table->dropColumn('city');
            $table->dropColumn('postal_code');
        });
    }
};
