<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hrm\AllowanceEmployee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hrm\AllowanceEmployee>
 */
final class AllowanceEmployeeFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = AllowanceEmployee::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'allowance_id' => \App\Models\Hrm\Allowance::factory(),
            'user_id' => \App\Models\User::factory(),
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
