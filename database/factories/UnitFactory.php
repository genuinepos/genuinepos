<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Unit>
 */
final class UnitFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Unit::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'code_name' => $this->faker->word,
            'dimension' => $this->faker->word,
        ];
    }
}
