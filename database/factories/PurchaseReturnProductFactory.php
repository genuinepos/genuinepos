<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PurchaseReturnProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\PurchaseReturnProduct>
 */
final class PurchaseReturnProductFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = PurchaseReturnProduct::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'purchase_return_id' => \App\Models\PurchaseReturn::factory(),
            'purchase_product_id' => \App\Models\PurchaseProduct::factory(),
            'product_id' => \App\Models\Product::factory(),
            'product_variant_id' => \App\Models\ProductVariant::factory(),
            'unit_cost' => $this->faker->randomFloat(),
            'return_qty' => $this->faker->randomFloat(),
            'unit' => $this->faker->word,
            'return_subtotal' => $this->faker->randomFloat(),
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
