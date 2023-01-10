<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ComboProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ComboProduct>
 */
final class ComboProductFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = ComboProduct::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->randomNumber(),
            'combo_product_id' => \App\Models\Product::factory(),
            'quantity' => $this->faker->randomFloat(),
            'product_variant_id' => \App\Models\ProductVariant::factory(),
            'delete_in_update' => $this->faker->boolean,
        ];
    }
}
