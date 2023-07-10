<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ExpansePayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ExpansePayment>
 */
final class ExpansePaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExpansePayment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'invoice_id' => $this->faker->word,
            'expanse_id' => \App\Models\Expanse::factory(),
            'account_id' => \App\Models\Account::factory(),
            'pay_mode' => $this->faker->word,
            'paid_amount' => $this->faker->randomFloat(),
            'payment_status' => $this->faker->boolean,
            'date' => $this->faker->word,
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
            'admin_id' => $this->faker->randomNumber(),
            'note' => $this->faker->sentence,
            'report_date' => $this->faker->word,
            'payment_method_id' => \App\Models\PaymentMethod::factory(),
        ];
    }
}
