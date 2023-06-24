<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SaleReturnProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\SaleReturnProduct>
 */
final class SaleReturnProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SaleReturnProduct::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sale_return_id' => \App\Models\SaleReturn::factory(),
            'sale_product_id' => \App\Models\SaleProduct::factory(),
            'product_id' => \App\Models\Product::factory(),
            'product_variant_id' => \App\Models\ProductVariant::factory(),
            'sold_quantity' => $this->faker->randomFloat(),
            'unit_cost_inc_tax' => $this->faker->randomFloat(),
            'unit_price_exc_tax' => $this->faker->randomFloat(),
            'unit_price_inc_tax' => $this->faker->randomFloat(),
            'unit_discount_type' => $this->faker->boolean,
            'unit_discount' => $this->faker->randomFloat(),
            'unit_discount_amount' => $this->faker->randomFloat(),
            'tax_type' => $this->faker->boolean,
            'unit_tax_percent' => $this->faker->randomFloat(),
            'unit_tax_amount' => $this->faker->randomFloat(),
            'return_qty' => $this->faker->randomFloat(),
            'unit' => $this->faker->word,
            'return_subtotal' => $this->faker->randomFloat(),
            'is_delete_in_update' => $this->faker->boolean,
            'sale_return_product_id' => \App\Models\PurchaseProduct::factory(),
        ];
    }
}
