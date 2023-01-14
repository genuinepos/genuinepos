<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hrm\Holiday;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hrm\Holiday>
 */
final class HolidayFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Holiday::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'holiday_name' => $this->faker->word,
            'start_date' => $this->faker->word,
            'end_date' => $this->faker->word,
            'branch_id' => \App\Models\Branch::factory(),
            'is_all' => $this->faker->boolean,
            'notes' => $this->faker->text,
        ];
    }
}
