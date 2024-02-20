<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CustomerCreditLimit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\CustomerCreditLimit>
 */
final class CustomerCreditLimitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerCreditLimit::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'customer_id' => $this->faker->randomNumber(),
            'branch_id' => $this->faker->randomNumber(),
            'created_by_id' => $this->faker->randomNumber(),
            'customer_type' => $this->faker->boolean,
            'credit_limit' => $this->faker->randomFloat(),
            'pay_term' => $this->faker->boolean,
            'pay_term_number' => $this->faker->randomNumber(),
        ];
    }
}
