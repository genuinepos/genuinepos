<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\WarehouseBranch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\WarehouseBranch>
 */
final class WarehouseBranchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WarehouseBranch::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => \App\Models\Branch::factory(),
            'warehouse_id' => $this->faker->randomNumber(),
            'is_global' => $this->faker->boolean,
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
