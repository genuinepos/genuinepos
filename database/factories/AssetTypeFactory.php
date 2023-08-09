<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AssetType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\AssetType>
 */
final class AssetTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AssetType::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'asset_type_name' => $this->faker->word,
            'asset_type_code' => $this->faker->word,
        ];
    }
}
