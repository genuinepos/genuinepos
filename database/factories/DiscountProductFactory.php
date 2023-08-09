<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DiscountProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\DiscountProduct>
 */
final class DiscountProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiscountProduct::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'discount_id' => $this->faker->randomNumber(),
            'product_id' => $this->faker->randomNumber(),
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
