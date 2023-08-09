<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Discount>
 */
final class DiscountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Discount::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'branch_id' => $this->faker->randomNumber(),
            'priority' => $this->faker->randomNumber(),
            'start_at' => $this->faker->date(),
            'end_at' => $this->faker->date(),
            'brand_id' => $this->faker->randomNumber(),
            'category_id' => $this->faker->randomNumber(),
            'discount_type' => $this->faker->boolean,
            'discount_amount' => $this->faker->randomFloat(),
            'price_group_id' => $this->faker->randomNumber(),
            'apply_in_customer_group' => $this->faker->boolean,
            'is_active' => $this->faker->boolean,
        ];
    }
}
