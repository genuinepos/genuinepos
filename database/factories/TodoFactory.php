<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Essential\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Essential\Todo>
 */
final class TodoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Todo::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'task' => $this->faker->word,
            'todo_id' => $this->faker->word,
            'priority' => $this->faker->word,
            'status' => $this->faker->word,
            'due_date' => $this->faker->word,
            'description' => $this->faker->text,
            'branch_id' => $this->faker->randomNumber(),
            'admin_id' => \App\Models\User::factory(),
        ];
    }
}
