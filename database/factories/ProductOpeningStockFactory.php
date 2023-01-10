<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProductOpeningStock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ProductOpeningStock>
 */
final class ProductOpeningStockFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = ProductOpeningStock::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'branch_id' => \App\Models\Branch::factory(),
            'warehouse_id' => $this->faker->randomNumber(),
            'product_id' => \App\Models\Product::factory(),
            'product_variant_id' => \App\Models\ProductVariant::factory(),
            'unit_cost_inc_tax' => $this->faker->randomFloat(),
            'quantity' => $this->faker->randomFloat(),
            'subtotal' => $this->faker->randomFloat(),
            'lot_no' => $this->faker->word,
        ];
    }
}
