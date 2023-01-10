<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AdminUserBranch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\AdminUserBranch>
 */
final class AdminUserBranchFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = AdminUserBranch::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
        ];
    }
}
