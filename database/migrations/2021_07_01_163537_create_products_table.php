<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->boolean('type')->comment('1=general,2=combo,3=digital');
			$table->string('name');
			$table->string('product_code');
			$table->bigInteger('category_id')->unsigned()->nullable()->index('products_category_id_foreign');
			$table->bigInteger('parent_category_id')->unsigned()->nullable()->index('products_parent_category_id_foreign');
			$table->bigInteger('brand_id')->unsigned()->nullable()->index('products_brand_id_foreign');
			$table->bigInteger('unit_id')->unsigned()->nullable()->index('products_unit_id_foreign');
			$table->bigInteger('tax_id')->unsigned()->nullable()->index('products_tax_id_foreign');
			$table->bigInteger('warranty_id')->unsigned()->nullable()->index('products_warranty_id_foreign');
			$table->decimal('product_cost', 22)->default(0.00);
			$table->decimal('product_cost_with_tax', 22)->default(0.00);
			$table->decimal('profit', 22)->default(0.00);
			$table->decimal('product_price', 22)->default(0.00);
			$table->decimal('offer_price', 22)->default(0.00);
			$table->decimal('quantity', 22)->default(0.00);
			$table->decimal('combo_price', 22)->default(0.00);
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
			$table->string('is_purchased')->default('0');
			$table->string('barcode_type')->nullable();
			$table->string('weight', 191)->nullable();
			$table->string('product_condition', 191)->nullable();
			$table->boolean('status')->default(1);
			$table->decimal('number_of_sale', 22)->default(0.00);
			$table->decimal('total_transfered', 22)->default(0.00);
			$table->decimal('total_adjusted', 22)->default(0.00);
			$table->string('custom_field_1', 191)->nullable();
			$table->string('custom_field_2', 191)->nullable();
			$table->string('custom_field_3', 191)->nullable();
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
		Schema::drop('products');
	}

}
