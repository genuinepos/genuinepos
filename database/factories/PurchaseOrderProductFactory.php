<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PurchaseOrderProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\PurchaseOrderProduct>
 */
final class PurchaseOrderProductFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = PurchaseOrderProduct::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'purchase_id' => \App\Models\Purchase::factory(),
            'product_id' => \App\Models\Product::factory(),
            'product_variant_id' => \App\Models\ProductVariant::factory(),
            'order_quantity' => $this->faker->randomFloat(),
            'received_quantity' => $this->faker->randomFloat(),
            'pending_quantity' => $this->faker->randomFloat(),
            'unit' => $this->faker->word,
            'unit_cost' => $this->faker->randomFloat(),
            'unit_discount' => $this->faker->randomFloat(),
            'unit_cost_with_discount' => $this->faker->randomFloat(),
            'subtotal' => $this->faker->randomFloat(),
            'tax_id' => $this->faker->randomNumber(),
            'unit_tax_percent' => $this->faker->randomFloat(),
            'unit_tax' => $this->faker->randomFloat(),
            'net_unit_cost' => $this->faker->randomFloat(),
            'ordered_unit_cost' => $this->faker->randomFloat(),
            'line_total' => $this->faker->randomFloat(),
            'profit_margin' => $this->faker->randomFloat(),
            'selling_price' => $this->faker->randomFloat(),
            'description' => $this->faker->text,
            'lot_no' => $this->faker->word,
            'delete_in_update' => $this->faker->boolean,
        ];
    }
}
