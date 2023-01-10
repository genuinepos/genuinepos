<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PurchasePayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\PurchasePayment>
 */
final class PurchasePaymentFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = PurchasePayment::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'invoice_id' => $this->faker->word,
            'purchase_id' => \App\Models\Purchase::factory(),
            'supplier_return_id' => $this->faker->randomNumber(),
            'supplier_id' => \App\Models\Supplier::factory(),
            'supplier_payment_id' => $this->faker->randomNumber(),
            'account_id' => \App\Models\Account::factory(),
            'pay_mode' => $this->faker->word,
            'payment_method_id' => \App\Models\PaymentMethod::factory(),
            'paid_amount' => $this->faker->randomFloat(),
            'payment_on' => $this->faker->boolean,
            'payment_type' => $this->faker->boolean,
            'payment_status' => $this->faker->boolean,
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
            'admin_id' => $this->faker->randomNumber(),
            'note' => $this->faker->sentence,
            'attachment' => $this->faker->word,
            'is_advanced' => $this->faker->boolean,
        ];
    }
}
