<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Asset>
 */
final class AssetFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Asset::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'asset_name' => $this->faker->word,
            'type_id' => $this->faker->randomNumber(),
            'branch_id' => $this->faker->randomNumber(),
            'quantity' => $this->faker->randomFloat(),
            'per_unit_value' => $this->faker->randomFloat(),
            'total_value' => $this->faker->randomFloat(),
        ];
    }
}
