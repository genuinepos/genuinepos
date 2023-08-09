<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\StockAdjustmentProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\StockAdjustmentProduct>
 */
final class StockAdjustmentProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockAdjustmentProduct::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'stock_adjustment_id' => \App\Models\StockAdjustment::factory(),
            'product_id' => \App\Models\Product::factory(),
            'product_variant_id' => \App\Models\ProductVariant::factory(),
            'quantity' => $this->faker->randomFloat(),
            'unit' => $this->faker->word,
            'unit_cost_inc_tax' => $this->faker->randomFloat(),
            'subtotal' => $this->faker->randomFloat(),
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
