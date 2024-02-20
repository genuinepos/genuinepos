<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SaleProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\SaleProduct>
 */
final class SaleProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SaleProduct::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sale_id' => \App\Models\Sale::factory(),
            'product_id' => \App\Models\Product::factory(),
            'product_variant_id' => \App\Models\ProductVariant::factory(),
            'quantity' => $this->faker->randomFloat(),
            'unit' => $this->faker->word,
            'unit_discount_type' => $this->faker->boolean,
            'unit_discount' => $this->faker->randomFloat(),
            'unit_discount_amount' => $this->faker->randomFloat(),
            'unit_tax_percent' => $this->faker->randomFloat(),
            'unit_tax_amount' => $this->faker->randomFloat(),
            'unit_cost_inc_tax' => $this->faker->randomFloat(),
            'unit_price_exc_tax' => $this->faker->randomFloat(),
            'unit_price_inc_tax' => $this->faker->randomFloat(),
            'subtotal' => $this->faker->randomFloat(),
            'description' => $this->faker->text,
            'ex_quantity' => $this->faker->randomFloat(),
            'ex_status' => $this->faker->boolean,
            'delete_in_update' => $this->faker->boolean,
            'stock_branch_id' => \App\Models\Branch::factory(),
            'stock_warehouse_id' => \App\Models\Warehouse::factory(),
        ];
    }
}
