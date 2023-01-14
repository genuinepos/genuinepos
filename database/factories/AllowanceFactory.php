<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hrm\Allowance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hrm\Allowance>
 */
final class AllowanceFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Allowance::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'description' => $this->faker->text,
            'type' => $this->faker->word,
            'employee_id' => $this->faker->randomNumber(),
            'amount_type' => $this->faker->boolean,
            'amount' => $this->faker->randomFloat(),
            'applicable_date' => $this->faker->word,
        ];
    }
}
