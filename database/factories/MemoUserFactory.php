<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Essential\MemoUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Essential\MemoUser>
 */
final class MemoUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MemoUser::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'memo_id' => $this->faker->randomNumber(),
            'user_id' => $this->faker->randomNumber(),
            'is_delete_in_update' => $this->faker->boolean,
            'is_author' => $this->faker->boolean,
        ];
    }
}
