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
        Schema::table('barcode_settings', function (Blueprint $table) {
            $table->string('company_name_size', 191)->after('is_continuous')->nullable()->comment('pixel');
            $table->boolean('company_name_bold_or_regular')->after('company_name_size')->default(1)->comment('0=regular,1=bold');
            $table->string('product_code_size', 191)->after('company_name_bold_or_regular')->nullable()->comment('pixel');
            $table->string('product_name_size', 191)->after('product_code_size')->nullable()->comment('pixel');
            $table->boolean('product_name_bold_or_regular')->after('product_name_size')->default(0)->comment('0=regular,1=bold');
            $table->string('price_size', 191)->after('product_name_bold_or_regular')->nullable()->comment('pixel');
            $table->boolean('price_bold_or_regular')->after('price_size')->default(0)->comment('0=regular,1=bold');
            $table->string('bar_width', 191)->after('price_bold_or_regular')->nullable()->comment('percent');
            $table->string('bar_height', 191)->after('bar_width')->nullable()->comment('percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barcode_settings', function (Blueprint $table) {
            //
        });
    }
};
