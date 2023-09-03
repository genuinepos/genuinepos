<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CustomerPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\CustomerPayment>
 */
final class CustomerPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerPayment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'voucher_no' => $this->faker->word,
            'reference' => $this->faker->word,
            'branch_id' => \App\Models\Branch::factory(),
            'customer_id' => \App\Models\Customer::factory(),
            'account_id' => \App\Models\Account::factory(),
            'paid_amount' => $this->faker->randomFloat(),
            'less_amount' => $this->faker->randomFloat(),
            'report_date' => $this->faker->word,
            'type' => $this->faker->boolean,
            'pay_mode' => $this->faker->word,
            'date' => $this->faker->word,
            'time' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
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
            'payment_method_id' => \App\Models\Setups\PaymentMethod::factory(),
        ];
    }
}
