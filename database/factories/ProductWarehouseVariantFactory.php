<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProductWarehouseVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ProductWarehouseVariant>
 */
final class ProductWarehouseVariantFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = ProductWarehouseVariant::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'product_warehouse_id' => \App\Models\ProductWarehouse::factory(),
            'product_id' => \App\Models\Product::factory(),
            'product_variant_id' => \App\Models\ProductVariant::factory(),
            'variant_quantity' => $this->faker->randomFloat(),
            'total_purchased' => $this->faker->randomFloat(),
            'total_adjusted' => $this->faker->randomFloat(),
            'total_transferred' => $this->faker->randomFloat(),
            'total_received' => $this->faker->randomFloat(),
            'total_sale_return' => $this->faker->randomFloat(),
            'total_purchase_return' => $this->faker->randomFloat(),
        ];
    }
}
