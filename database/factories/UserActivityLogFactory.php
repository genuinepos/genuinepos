<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\UserActivityLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\UserActivityLog>
 */
final class UserActivityLogFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = UserActivityLog::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'user_id' => $this->faker->randomNumber(),
            'date' => $this->faker->word,
            'report_date' => $this->faker->word,
            'action' => $this->faker->boolean,
            'subject_type' => $this->faker->randomNumber(),
            'descriptions' => $this->faker->text,
        ];
    }
}
