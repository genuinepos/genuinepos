<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ProductVariant>
 */
final class ProductVariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductVariant::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'variant_name' => $this->faker->word,
            'variant_code' => $this->faker->word,
            'variant_quantity' => $this->faker->randomFloat(),
            'number_of_sale' => $this->faker->randomFloat(),
            'total_transfered' => $this->faker->randomFloat(),
            'total_adjusted' => $this->faker->randomFloat(),
            'variant_cost' => $this->faker->randomFloat(),
            'variant_cost_with_tax' => $this->faker->randomFloat(),
            'variant_profit' => $this->faker->randomFloat(),
            'variant_price' => $this->faker->randomFloat(),
            'variant_image' => $this->faker->word,
            'is_purchased' => $this->faker->boolean,
            'delete_in_update' => $this->faker->boolean,
        ];
    }
}
