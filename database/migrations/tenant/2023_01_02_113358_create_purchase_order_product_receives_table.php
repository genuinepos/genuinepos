<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_product_receives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_product_id')->index('purchase_order_product_receives_order_product_id_foreign');
            $table->string('purchase_challan')->nullable();
            $table->unsignedBigInteger('lot_number')->nullable();
            $table->string('received_date')->nullable();
            $table->decimal('qty_received', 22)->default(0);
            $table->timestamp('report_date')->nullable();
            $table->boolean('is_delete_in_update')->default(false);
            $table->timestamps();

            $table->foreign(['order_product_id'])->references(['id'])->on('purchase_order_products')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_product_receives');
    }
};
