<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\LoanPaymentDistribution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\LoanPaymentDistribution>
 */
final class LoanPaymentDistributionFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = LoanPaymentDistribution::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'loan_payment_id' => $this->faker->randomNumber(),
            'loan_id' => \App\Models\Loan::factory(),
            'paid_amount' => $this->faker->randomFloat(),
            'payment_type' => $this->faker->boolean,
        ];
    }
}
