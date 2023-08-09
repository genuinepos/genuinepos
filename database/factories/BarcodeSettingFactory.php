<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\BarcodeSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\BarcodeSetting>
 */
final class BarcodeSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BarcodeSetting::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'is_continuous' => $this->faker->boolean,
            'top_margin' => $this->faker->randomFloat(),
            'left_margin' => $this->faker->randomFloat(),
            'sticker_width' => $this->faker->randomFloat(),
            'sticker_height' => $this->faker->randomFloat(),
            'paper_width' => $this->faker->randomFloat(),
            'paper_height' => $this->faker->randomFloat(),
            'row_distance' => $this->faker->randomFloat(),
            'column_distance' => $this->faker->randomFloat(),
            'stickers_in_a_row' => $this->faker->randomNumber(),
            'stickers_in_one_sheet' => $this->faker->randomNumber(),
            'is_default' => $this->faker->boolean,
            'is_fixed' => $this->faker->boolean,
        ];
    }
}
