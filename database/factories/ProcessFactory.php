<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Manufacturing\Process;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Manufacturing\Process>
 */
final class ProcessFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Process::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'product_id' => \App\Models\Product::factory(),
            'variant_id' => \App\Models\ProductVariant::factory(),
            'total_ingredient_cost' => $this->faker->randomFloat(),
            'wastage_percent' => $this->faker->randomFloat(),
            'wastage_amount' => $this->faker->randomFloat(),
            'total_output_qty' => $this->faker->randomFloat(),
            'unit_id' => \App\Models\Unit::factory(),
            'production_cost' => $this->faker->randomFloat(),
            'total_cost' => $this->faker->randomFloat(),
            'process_instruction' => $this->faker->text,
        ];
    }
}
