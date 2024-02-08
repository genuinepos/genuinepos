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

            if (Schema::hasColumn('custom_field_1', 'custom_field_2', 'social_media_1', 'social_media_2')) {

                $table->dropColumn('custom_field_1');
                $table->dropColumn('custom_field_2');
                $table->dropColumn('social_media_1');
                $table->dropColumn('social_media_2');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('custom_field_1')->after('instagram_link')->nullable();
            $table->string('custom_field_2')->after('custom_field_1')->nullable();
            $table->string('social_media_1')->after('custom_field_2')->nullable();
            $table->string('social_media_2')->after('social_media_1')->nullable();
        });
    }
};
