<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TransferStockBranchToBranchProducts;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\TransferStockBranchToBranchProducts>
 */
final class TransferStockBranchToBranchProductsFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = TransferStockBranchToBranchProducts::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'transfer_id' => $this->faker->randomNumber(),
            'product_id' => \App\Models\Product::factory(),
            'variant_id' => \App\Models\ProductVariant::factory(),
            'unit_cost_inc_tax' => $this->faker->randomFloat(),
            'unit_price_inc_tax' => $this->faker->randomFloat(),
            'subtotal' => $this->faker->randomFloat(),
            'send_qty' => $this->faker->randomFloat(),
            'received_qty' => $this->faker->randomFloat(),
            'pending_qty' => $this->faker->randomFloat(),
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
