<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Manufacturing\Production;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Manufacturing\Production>
 */
final class ProductionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Production::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'unit_id' => \App\Models\Unit::factory(),
            'tax_id' => \App\Models\Tax::factory(),
            'tax_type' => $this->faker->boolean,
            'reference_no' => $this->faker->word,
            'date' => $this->faker->word,
            'time' => $this->faker->word,
            'report_date' => $this->faker->word,
            'warehouse_id' => \App\Models\Warehouse::factory(),
            'stock_warehouse_id' => \App\Models\Warehouse::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'stock_branch_id' => \App\Models\Branch::factory(),
            'product_id' => \App\Models\Product::factory(),
            'variant_id' => \App\Models\ProductVariant::factory(),
            'total_ingredient_cost' => $this->faker->randomFloat(),
            'quantity' => $this->faker->randomFloat(),
            'parameter_quantity' => $this->faker->randomFloat(),
            'wasted_quantity' => $this->faker->randomFloat(),
            'total_final_quantity' => $this->faker->randomFloat(),
            'unit_cost_exc_tax' => $this->faker->randomFloat(),
            'unit_cost_inc_tax' => $this->faker->randomFloat(),
            'x_margin' => $this->faker->randomFloat(),
            'price_exc_tax' => $this->faker->randomFloat(),
            'production_cost' => $this->faker->randomFloat(),
            'total_cost' => $this->faker->randomFloat(),
            'is_final' => $this->faker->boolean,
            'is_last_entry' => $this->faker->boolean,
            'is_default_price' => $this->faker->boolean,
            'production_account_id' => $this->faker->randomNumber(),
        ];
    }
}
