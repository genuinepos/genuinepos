<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hrm\PayrollPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hrm\PayrollPayment>
 */
final class PayrollPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PayrollPayment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'reference_no' => $this->faker->word,
            'payroll_id' => \App\Models\Hrm\Payroll::factory(),
            'account_id' => \App\Models\Account::factory(),
            'paid' => $this->faker->randomFloat(),
            'due' => $this->faker->randomFloat(),
            'pay_mode' => $this->faker->word,
            'payment_method_id' => \App\Models\PaymentMethod::factory(),
            'date' => $this->faker->word,
            'time' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
            'report_date' => $this->faker->word,
            'card_no' => $this->faker->word,
            'card_holder' => $this->faker->word,
            'card_type' => $this->faker->word,
            'card_transaction_no' => $this->faker->word,
            'card_month' => $this->faker->word,
            'card_year' => $this->faker->word,
            'card_secure_code' => $this->faker->word,
            'account_no' => $this->faker->word,
            'cheque_no' => $this->faker->word,
            'transaction_no' => $this->faker->word,
            'attachment' => $this->faker->word,
            'note' => $this->faker->sentence,
            'admin_id' => $this->faker->randomNumber(),
        ];
    }
}
