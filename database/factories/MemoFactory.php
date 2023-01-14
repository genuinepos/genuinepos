<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Essential\Memo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Essential\Memo>
 */
final class MemoFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Memo::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'heading' => $this->faker->word,
            'description' => $this->faker->text,
            'admin_id' => $this->faker->randomNumber(),
        ];
    }
}
