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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type')->comment('1=general,2=combo,3=digital');
            $table->string('name');
            $table->string('product_code');
            $table->unsignedBigInteger('category_id')->nullable()->index('products_category_id_foreign');
            $table->unsignedBigInteger('sub_category_id')->nullable()->index('products_sub_category_id_foreign');
            $table->unsignedBigInteger('brand_id')->nullable()->index('products_brand_id_foreign');
            $table->unsignedBigInteger('unit_id')->nullable()->index('products_unit_id_foreign');
            $table->unsignedBigInteger('tax_ac_id')->nullable();
            $table->tinyInteger('tax_type')->default(1);
            $table->unsignedBigInteger('warranty_id')->nullable()->index('products_warranty_id_foreign');
            $table->decimal('product_cost', 22, 2)->default(0);
            $table->decimal('product_cost_with_tax', 22, 2)->default(0);
            $table->decimal('profit', 22, 2)->default(0);
            $table->decimal('product_price', 22, 2)->default(0);
            $table->decimal('offer_price', 22, 2)->default(0);
            $table->boolean('is_manage_stock')->default(true);
            $table->decimal('quantity', 22, 2)->default(0);
            $table->decimal('combo_price', 22, 2)->default(0);
            $table->bigInteger('alert_quantity')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_combo')->default(false);
            $table->boolean('has_multiple_unit')->default(false);
            $table->boolean('is_variant')->default(false);
            $table->boolean('is_show_in_ecom')->default(false);
            $table->boolean('is_show_emi_on_pos')->default(false);
            $table->boolean('has_batch_no_expire_date')->default(0);
            $table->boolean('is_for_sale')->default(true);
            $table->string('thumbnail_photo')->nullable();
            $table->string('expire_date')->nullable();
            $table->text('product_details')->nullable();
            $table->string('is_purchased')->default('0');
            $table->string('barcode_type')->nullable();
            $table->string('weight', 191)->nullable();
            $table->string('product_condition', 191)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('tax_ac_id')->references('id')->on('accounts')->onDelete('cascade');
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
};
