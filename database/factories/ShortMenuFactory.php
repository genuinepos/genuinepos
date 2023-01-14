<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ShortMenu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ShortMenu>
 */
final class ShortMenuFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = ShortMenu::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'url' => $this->faker->url,
            'name' => $this->faker->name,
            'icon' => $this->faker->word,
        ];
    }
}
