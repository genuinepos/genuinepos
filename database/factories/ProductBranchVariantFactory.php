<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProductBranchVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ProductBranchVariant>
 */
final class ProductBranchVariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductBranchVariant::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_branch_id' => \App\Models\ProductBranch::factory(),
            'product_id' => \App\Models\Product::factory(),
            'product_variant_id' => \App\Models\ProductVariant::factory(),
            'variant_quantity' => $this->faker->randomFloat(),
            'total_sale' => $this->faker->randomFloat(),
            'total_purchased' => $this->faker->randomFloat(),
            'total_adjusted' => $this->faker->randomFloat(),
            'total_transferred' => $this->faker->randomFloat(),
            'total_received' => $this->faker->randomFloat(),
            'total_opening_stock' => $this->faker->randomFloat(),
            'total_sale_return' => $this->faker->randomFloat(),
            'total_purchase_return' => $this->faker->randomFloat(),
        ];
    }
}
