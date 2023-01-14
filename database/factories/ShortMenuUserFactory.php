<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ShortMenuUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ShortMenuUser>
 */
final class ShortMenuUserFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = ShortMenuUser::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'short_menu_id' => $this->faker->randomNumber(),
            'user_id' => $this->faker->randomNumber(),
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
