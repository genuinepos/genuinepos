<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\LoanPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\LoanPayment>
 */
final class LoanPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LoanPayment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'voucher_no' => $this->faker->word,
            'company_id' => \App\Models\LoanCompany::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'account_id' => $this->faker->randomNumber(),
            'paid_amount' => $this->faker->randomFloat(),
            'pay_mode' => $this->faker->word,
            'payment_method_id' => \App\Models\Setups\PaymentMethod::factory(),
            'date' => $this->faker->word,
            'report_date' => $this->faker->word,
            'user_id' => $this->faker->randomNumber(),
            'payment_type' => $this->faker->boolean,
            'loan_id' => \App\Models\Loan::factory(),
        ];
    }
}
