<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hrm\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hrm\Attendance>
 */
final class AttendanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'at_date' => $this->faker->word,
            'user_id' => $this->faker->randomNumber(),
            'clock_in' => $this->faker->word,
            'clock_out' => $this->faker->word,
            'work_duration' => $this->faker->word,
            'clock_in_note' => $this->faker->text,
            'clock_out_note' => $this->faker->text,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
            'clock_in_ts' => $this->faker->word,
            'clock_out_ts' => $this->faker->word,
            'at_date_ts' => $this->faker->word,
            'is_completed' => $this->faker->boolean,
        ];
    }
}
