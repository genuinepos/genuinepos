<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Product>
 */
final class ProductFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Product::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'type' => $this->faker->boolean,
            'name' => $this->faker->name,
            'product_code' => $this->faker->word,
            'category_id' => \App\Models\Category::factory(),
            'parent_category_id' => \App\Models\Category::factory(),
            'brand_id' => \App\Models\Brand::factory(),
            'unit_id' => \App\Models\Unit::factory(),
            'tax_id' => \App\Models\Tax::factory(),
            'tax_type' => $this->faker->boolean,
            'warranty_id' => \App\Models\Warranty::factory(),
            'product_cost' => $this->faker->randomFloat(),
            'product_cost_with_tax' => $this->faker->randomFloat(),
            'profit' => $this->faker->randomFloat(),
            'product_price' => $this->faker->randomFloat(),
            'offer_price' => $this->faker->randomFloat(),
            'is_manage_stock' => $this->faker->boolean,
            'quantity' => $this->faker->randomFloat(),
            'combo_price' => $this->faker->randomFloat(),
            'alert_quantity' => $this->faker->randomNumber(),
            'is_featured' => $this->faker->boolean,
            'is_combo' => $this->faker->boolean,
            'is_variant' => $this->faker->boolean,
            'is_show_in_ecom' => $this->faker->boolean,
            'is_show_emi_on_pos' => $this->faker->boolean,
            'is_for_sale' => $this->faker->boolean,
            'attachment' => $this->faker->word,
            'thumbnail_photo' => $this->faker->word,
            'expire_date' => $this->faker->word,
            'product_details' => $this->faker->text,
            'is_purchased' => $this->faker->word,
            'barcode_type' => $this->faker->word,
            'weight' => $this->faker->word,
            'product_condition' => $this->faker->word,
            'status' => $this->faker->boolean,
            'number_of_sale' => $this->faker->randomFloat(),
            'total_transfered' => $this->faker->randomFloat(),
            'total_adjusted' => $this->faker->randomFloat(),
            'custom_field_1' => $this->faker->word,
            'custom_field_2' => $this->faker->word,
            'custom_field_3' => $this->faker->word,
        ];
    }
}
