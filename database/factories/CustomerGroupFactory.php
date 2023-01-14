<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CustomerGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\CustomerGroup>
 */
final class CustomerGroupFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = CustomerGroup::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'group_name' => $this->faker->word,
            'calc_percentage' => $this->faker->randomFloat(),
        ];
    }
}
