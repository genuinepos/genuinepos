<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CustomerOpeningBalance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\CustomerOpeningBalance>
 */
final class CustomerOpeningBalanceFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = CustomerOpeningBalance::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'customer_id' => $this->faker->randomNumber(),
            'amount' => $this->faker->randomFloat(),
            'report_date' => $this->faker->word,
            'is_show_again' => $this->faker->boolean,
            'created_by_id' => $this->faker->randomNumber(),
        ];
    }
}
