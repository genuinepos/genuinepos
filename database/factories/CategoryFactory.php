<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Category>
 */
final class CategoryFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Category::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'name' => 'Category - ' . \Str::random(10),
            'description' => $this->faker->sentence(4),
            'parent_category_id' => null,
            'photo' => $this->faker->imageUrl(),
            'status' => $this->faker->boolean,
        ];
    }
}
