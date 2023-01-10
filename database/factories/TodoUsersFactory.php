<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Essential\TodoUsers;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Essential\TodoUsers>
 */
final class TodoUsersFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = TodoUsers::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'todo_id' => $this->faker->randomNumber(),
            'user_id' => \App\Models\User::factory(),
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
