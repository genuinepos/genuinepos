<?php

declare(strict_types=1);

namespace Database\Factories\Hrm;

use App\Models\Hrm\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hrm\LeaveType>
 */
final class LeaveTypeFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = LeaveType::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'leave_type' => $this->faker->word,
            'max_leave_count' => $this->faker->randomNumber(),
            'leave_count_interval' => $this->faker->randomNumber(),
        ];
    }
}
