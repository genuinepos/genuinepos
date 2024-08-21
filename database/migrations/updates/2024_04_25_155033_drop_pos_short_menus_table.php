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
        Schema::table('pos_short_menus', function (Blueprint $table) {

            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('pos_short_menus');
            Schema::enableForeignKeyConstraints();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_short_menus', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('url')->nullable();
            $table->string('name')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }
};
