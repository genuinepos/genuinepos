<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Manufacturing\ProductionIngredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Manufacturing\ProductionIngredient>
 */
final class ProductionIngredientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductionIngredient::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'production_id' => $this->faker->randomNumber(),
            'product_id' => \App\Models\Product::factory(),
            'variant_id' => \App\Models\ProductVariant::factory(),
            'parameter_quantity' => $this->faker->randomFloat(),
            'input_qty' => $this->faker->randomFloat(),
            'wastage_percent' => $this->faker->randomFloat(),
            'final_qty' => $this->faker->randomFloat(),
            'unit_id' => \App\Models\Unit::factory(),
            'unit_cost_inc_tax' => $this->faker->randomFloat(),
            'subtotal' => $this->faker->randomFloat(),
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
