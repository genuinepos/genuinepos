<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Essential\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Essential\Message>
 */
final class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'user_id' => $this->faker->randomNumber(),
            'description' => $this->faker->text,
        ];
    }
}
