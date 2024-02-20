<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AccountBranch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\AccountBranch>
 */
final class AccountBranchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccountBranch::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'account_id' => $this->faker->randomNumber(),
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
