<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Essential\WorkspaceTask;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Essential\WorkspaceTask>
 */
final class WorkspaceTaskFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = WorkspaceTask::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'workspace_id' => $this->faker->randomNumber(),
            'task_name' => $this->faker->word,
            'user_id' => $this->faker->randomNumber(),
            'deadline' => $this->faker->word,
            'status' => $this->faker->word,
            'priority' => $this->faker->word,
        ];
    }
}
