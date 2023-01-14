<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ProductImage>
 */
final class ProductImageFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = ProductImage::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->randomNumber(),
            'image' => $this->faker->word,
        ];
    }
}
