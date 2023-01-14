<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hrm\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hrm\Department>
 */
final class DepartmentFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Department::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'department_name' => $this->faker->word,
            'department_id' => $this->faker->word,
            'description' => $this->faker->text,
        ];
    }
}
