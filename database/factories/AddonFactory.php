<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Addon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Addon>
 */
final class AddonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Addon::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branches' => $this->faker->boolean,
            'hrm' => $this->faker->boolean,
            'todo' => $this->faker->boolean,
            'service' => $this->faker->boolean,
            'manufacturing' => $this->faker->boolean,
            'e_commerce' => $this->faker->boolean,
            'branch_limit' => $this->faker->randomNumber(),
            'cash_counter_limit' => $this->faker->randomNumber(),
        ];
    }
}
