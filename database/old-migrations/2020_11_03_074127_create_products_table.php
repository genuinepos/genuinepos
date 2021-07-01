<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->comment('1=general,2=combo,3=digital');
            $table->string('name');
            $table->string('product_code');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('parent_category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('tax_type')->nullable();
            $table->unsignedBigInteger('warranty_id')->nullable();
            $table->decimal('product_cost', 10,2)->default(0);
            $table->decimal('product_cost_with_tax', 10,2)->default(0);
            $table->decimal('profit', 22,2)->default(0);
            $table->decimal('product_price', 22,2);
            $table->decimal('offer_price', 22,2)->default(0);
            $table->decimal('quantity', 22, 2)->default(0.00);
            $table->decimal('number_of_sale', 22, 2)->default(0.00);
            $table->decimal('total_tranfered', 22, 2)->default(0.00);
            $table->decimal('total_adjusted', 22, 2)->default(0.00);
            $table->string('weight')->nullable();
            $table->decimal('combo_price', 22,2)->default(0.00);
            $table->integer('alert_quantity')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_combo')->default(0);
            $table->boolean('is_variant')->default(0);
            $table->boolean('is_show_in_ecom')->default(0);
            $table->boolean('is_show_emi_on_pos')->default(0);
            $table->boolean('is_for_sale')->default(1);
            $table->string('attachment')->nullable();
            $table->string('thumbnail_photo')->default('default.png');
            $table->string('expire_date')->nullable();
            $table->text('product_details')->nullable();
            $table->string('is_purchased')->default(0);
            $table->string('barcode_type')->nullable();
            $table->boolean('product_condition')->nullable();
            $table->boolean('status')->default(1);
            $table->string('custom_field_1')->nullable();
            $table->string('custom_field_2')->nullable();
            $table->string('custom_field_3')->nullable();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('parent_category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('cascade');
            $table->foreign('warranty_id')->references('id')->on('warranties')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
