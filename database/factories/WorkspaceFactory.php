<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Essential\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Essential\Workspace>
 */
final class WorkspaceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Workspace::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'ws_id' => $this->faker->word,
            'name' => $this->faker->name,
            'priority' => $this->faker->word,
            'status' => $this->faker->word,
            'start_date' => $this->faker->word,
            'end_date' => $this->faker->word,
            'admin_id' => \App\Models\User::factory(),
            'description' => $this->faker->text,
            'estimated_hours' => $this->faker->word,
        ];
    }
}
