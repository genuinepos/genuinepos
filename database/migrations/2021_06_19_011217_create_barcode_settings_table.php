<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarcodeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcode_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->mediumText('description')->nullable();
            $table->boolean('is_continuous')->default(0);
            $table->bigInteger('top_margin')->default(0);
            $table->bigInteger('left_margin')->default(0);
            $table->bigInteger('sticker_width')->default(0);
            $table->bigInteger('sticker_height')->default(0);
            $table->bigInteger('paper_width')->default(0);
            $table->bigInteger('paper_height')->default(0);
            $table->bigInteger('row_distance')->default(0);
            $table->bigInteger('column_distance')->default(0);
            $table->bigInteger('stickers_in_a_row')->default(0);
            $table->bigInteger('stickers_in_one_sheet')->default(0);
            $table->boolean('is_default')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barcode_settings');
    }
}
