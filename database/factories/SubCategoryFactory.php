<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubCategory>
 */
class SubCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'Sub category - ' . \Str::random(10),
            'description' => $this->faker->sentence(4),
            'parent_category_id' => $this->faker->randomElement(Category::pluck('id')->toArray()),
            'photo' => $this->faker->imageUrl(),
            'status' => $this->faker->boolean,
        ];
    }
}
