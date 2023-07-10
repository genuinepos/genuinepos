<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PosShortMenu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\PosShortMenu>
 */
final class PosShortMenuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PosShortMenu::class;

    /**
     * Define the model's default state.
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
