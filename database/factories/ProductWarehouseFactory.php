<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProductWarehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ProductWarehouse>
 */
final class ProductWarehouseFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = ProductWarehouse::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'warehouse_id' => \App\Models\Warehouse::factory(),
            'product_id' => \App\Models\Product::factory(),
            'product_quantity' => $this->faker->randomFloat(),
            'total_purchased' => $this->faker->randomFloat(),
            'total_adjusted' => $this->faker->randomFloat(),
            'total_transferred' => $this->faker->randomFloat(),
            'total_received' => $this->faker->randomFloat(),
            'total_sale_return' => $this->faker->randomFloat(),
            'total_purchase_return' => $this->faker->randomFloat(),
        ];
    }
}
