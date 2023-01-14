<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TransferStockToWarehouseProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\TransferStockToWarehouseProduct>
 */
final class TransferStockToWarehouseProductFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = TransferStockToWarehouseProduct::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'transfer_stock_id' => $this->faker->randomNumber(),
            'product_id' => \App\Models\Product::factory(),
            'product_variant_id' => \App\Models\ProductVariant::factory(),
            'unit_price' => $this->faker->randomFloat(),
            'quantity' => $this->faker->randomFloat(),
            'received_qty' => $this->faker->randomFloat(),
            'unit' => $this->faker->word,
            'subtotal' => $this->faker->randomFloat(),
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
