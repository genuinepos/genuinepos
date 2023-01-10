<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Currency>
 */
final class CurrencyFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Currency::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'country' => $this->faker->country,
            'currency' => $this->faker->currencyCode,
            'code' => $this->faker->word,
            'symbol' => $this->faker->word,
            'thousand_separator' => $this->faker->word,
            'decimal_separator' => $this->faker->word,
        ];
    }
}
