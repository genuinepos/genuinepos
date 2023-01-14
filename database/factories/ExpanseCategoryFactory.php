<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ExpanseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ExpanseCategory>
 */
final class ExpanseCategoryFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = ExpanseCategory::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'name' => $this->faker->name,
            'code' => $this->faker->word,
        ];
    }
}
