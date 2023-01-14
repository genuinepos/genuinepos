<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PurchaseSaleProductChain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\PurchaseSaleProductChain>
 */
final class PurchaseSaleProductChainFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = PurchaseSaleProductChain::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'purchase_product_id' => \App\Models\PurchaseProduct::factory(),
            'sale_product_id' => $this->faker->randomNumber(),
            'sold_qty' => $this->faker->randomFloat(),
        ];
    }
}
