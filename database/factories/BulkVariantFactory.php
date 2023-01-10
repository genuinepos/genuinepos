<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\BulkVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\BulkVariant>
 */
final class BulkVariantFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = BulkVariant::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'bulk_variant_name' => $this->faker->word,
        ];
    }
}
