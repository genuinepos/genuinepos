<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CashCounter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\CashCounter>
 */
final class CashCounterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashCounter::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'counter_name' => $this->faker->word,
            'short_name' => $this->faker->word,
        ];
    }
}
