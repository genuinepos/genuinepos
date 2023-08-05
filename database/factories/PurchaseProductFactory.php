<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PurchaseProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\PurchaseProduct>
 */
final class PurchaseProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PurchaseProduct::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'purchase_id' => \App\Models\Purchase::factory(),
            'product_id' => \App\Models\Product::factory(),
            'product_variant_id' => \App\Models\ProductVariant::factory(),
            'quantity' => $this->faker->randomFloat(),
            'unit' => $this->faker->word,
            'unit_cost' => $this->faker->randomFloat(),
            'unit_discount' => $this->faker->randomFloat(),
            'unit_cost_with_discount' => $this->faker->randomFloat(),
            'subtotal' => $this->faker->randomFloat(),
            'unit_tax_percent' => $this->faker->randomFloat(),
            'unit_tax' => $this->faker->randomFloat(),
            'net_unit_cost' => $this->faker->randomFloat(),
            'line_total' => $this->faker->randomFloat(),
            'profit_margin' => $this->faker->randomFloat(),
            'selling_price' => $this->faker->randomFloat(),
            'description' => $this->faker->text,
            'is_received' => $this->faker->boolean,
            'lot_no' => $this->faker->word,
            'delete_in_update' => $this->faker->boolean,
            'product_order_product_id' => $this->faker->randomNumber(),
            'left_qty' => $this->faker->randomFloat(),
            'production_id' => $this->faker->randomNumber(),
            'opening_stock_id' => $this->faker->randomNumber(),
            'sale_return_product_id' => $this->faker->randomNumber(),
            'transfer_branch_to_branch_product_id' => $this->faker->randomNumber(),
        ];
    }
}
