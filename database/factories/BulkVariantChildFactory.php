<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\BulkVariantChild;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\BulkVariantChild>
 */
final class BulkVariantChildFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = BulkVariantChild::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'bulk_variant_id' => \App\Models\BulkVariant::factory(),
            'child_name' => $this->faker->word,
            'delete_in_update' => $this->faker->boolean,
        ];
    }
}
