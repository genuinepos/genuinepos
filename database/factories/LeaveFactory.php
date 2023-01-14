<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hrm\Leave;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hrm\Leave>
 */
final class LeaveFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Leave::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'leave_no' => $this->faker->word,
            'leave_type_id' => \App\Models\Hrm\LeaveType::factory(),
            'employee_id' => \App\Models\User::factory(),
            'start_date' => $this->faker->word,
            'end_date' => $this->faker->word,
            'reason' => $this->faker->text,
            'status' => $this->faker->randomNumber(),
        ];
    }
}
