<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hrm\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hrm\Shift>
 */
final class ShiftFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Shift::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'shift_name' => $this->faker->word,
            'start_time' => $this->faker->word,
            'late_count' => $this->faker->word,
            'endtime' => $this->faker->word,
        ];
    }
}
