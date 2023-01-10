<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Essential\WorkspaceUsers;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Essential\WorkspaceUsers>
 */
final class WorkspaceUsersFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = WorkspaceUsers::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'workspace_id' => \App\Models\Essential\Workspace::factory(),
            'user_id' => \App\Models\User::factory(),
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
