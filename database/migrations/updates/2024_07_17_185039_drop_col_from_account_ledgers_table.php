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
        Schema::table('account_ledgers', function (Blueprint $table) {

            if (Schema::hasColumn('account_ledgers', 'loan_id')) {

                Schema::disableForeignKeyConstraints();

                // Check if the foreign key exists before attempting to drop it
                $foreignKeys = DB::select(
                    "SELECT CONSTRAINT_NAME FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?",
                    [DB::getDatabaseName(), 'account_ledgers', 'account_ledgers_loan_id_foreign']
                );

                if (!empty($foreignKeys)) {
                    $table->dropForeign(['loan_id']);
                }
                $table->dropColumn('loan_id');

                Schema::enableForeignKeyConstraints();
            }

            if (Schema::hasColumn('account_ledgers', 'loan_payment_id')) {

                Schema::disableForeignKeyConstraints();

                // Check if the foreign key exists before attempting to drop it
                $foreignKeys = DB::select(
                    "SELECT CONSTRAINT_NAME FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?",
                    [DB::getDatabaseName(), 'account_ledgers', 'account_ledgers_loan_payment_id_foreign']
                );

                if (!empty($foreignKeys)) {
                    $table->dropForeign(['loan_payment_id']);
                }
                $table->dropColumn('loan_payment_id');

                Schema::enableForeignKeyConstraints();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->unsignedBigInteger('loan_id')->after('payroll_id')->nullable();
            $table->unsignedBigInteger('loan_payment_id')->after('loan_id')->nullable();

            $table->foreign(['loan_id'])->references(['id'])->on('loans')->onDelete('CASCADE');
            $table->foreign(['loan_payment_id'])->references(['id'])->on('loan_payments')->onDelete('CASCADE');
        });
    }
};
