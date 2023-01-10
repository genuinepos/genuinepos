<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Warranty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Warranty>
 */
final class WarrantyFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Warranty::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'duration' => $this->faker->word,
            'duration_type' => $this->faker->word,
            'description' => $this->faker->text,
            'type' => $this->faker->boolean,
        ];
    }
}
