<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Manufacturing\ProcessIngredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Manufacturing\ProcessIngredient>
 */
final class ProcessIngredientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProcessIngredient::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'process_id' => \App\Models\Manufacturing\Process::factory(),
            'product_id' => \App\Models\Product::factory(),
            'variant_id' => \App\Models\ProductVariant::factory(),
            'wastage_percent' => $this->faker->randomFloat(),
            'wastage_amount' => $this->faker->randomFloat(),
            'final_qty' => $this->faker->randomFloat(),
            'unit_id' => \App\Models\Unit::factory(),
            'unit_cost_inc_tax' => $this->faker->randomFloat(),
            'subtotal' => $this->faker->randomFloat(),
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
