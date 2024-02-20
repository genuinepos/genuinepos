<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\InvoiceSchema;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\InvoiceSchema>
 */
final class InvoiceSchemaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceSchema::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'format' => $this->faker->word,
            'start_from' => $this->faker->word,
            'number_of_digit' => $this->faker->boolean,
            'is_default' => $this->faker->boolean,
            'prefix' => $this->faker->word,
        ];
    }
}
